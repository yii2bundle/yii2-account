<?php

namespace yii2module\account\domain\v3\services;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2module\account\domain\v3\helpers\LoginTypeHelper;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\helpers\Helper;
use yii2rails\domain\services\base\BaseService;
use yii2rails\domain\traits\MethodEventTrait;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2rails\extension\yii\helpers\ArrayHelper;
use yii2module\account\domain\v3\behaviors\UserActivityFilter;
use yii2module\account\domain\v3\enums\AccountEventEnum;
use yii2module\account\domain\v3\events\AccountAuthenticationEvent;
use yii2module\account\domain\v3\filters\token\BaseTokenFilter;
use yii2module\account\domain\v3\filters\token\DefaultFilter;
use yii2module\account\domain\v3\forms\LoginForm;
use yii2module\account\domain\v3\helpers\AuthHelper;
use yii2module\account\domain\v3\helpers\TokenHelper;
use yii2module\account\domain\v3\interfaces\services\AuthInterface;
use yii\web\ServerErrorHttpException;
use yii2module\account\domain\v3\entities\LoginEntity;

/**
 * Class AuthService
 *
 * @package yii2module\account\domain\v3\services
 *
 * @property \yii2module\account\domain\v3\interfaces\repositories\AuthInterface $repository
 */
class AuthService extends BaseService implements AuthInterface {

	use MethodEventTrait;
	
    public $tokenAuthMethods = [
	    'bearer' => DefaultFilter::class,
    ];

	public function behaviors() {
		return [
			[
				'class' => UserActivityFilter::class,
				'methods' => ['authentication'],
			],
		];
	}

	public function authenticationFromApi(LoginForm $model) : LoginEntity {
		if(!$model->validate()) {
			throw new UnprocessableEntityHttpException($model);
		}
		$loginEntity = $this->authentication($model->login, $model->password);
		return $loginEntity;
	}


    public function authenticationFromWeb(LoginForm $model) : LoginEntity {
        if(!$model->validate()) {
            throw new UnprocessableEntityHttpException($model);
        }
		$loginEntity = $this->authentication($model->login, $model->password);
		$this->login($loginEntity, $model->rememberMe);
		return $loginEntity;
	}

	public function authenticationByToken(string $token, string $type = null) : LoginEntity {
		if(empty($token)) {
			throw new InvalidArgumentException('Empty token');
		}
		if(!LoginTypeHelper::isToken($token)) {
			throw new UnauthorizedHttpException('Invalid token format, want: "*** *************"');
		}
		$query = new Query;
		$query->with('assignments');
		try {
			$loginEntity = \App::$domain->account->login->oneByAny($token, $query);
		} catch(\Exception $e) {
			throw new UnauthorizedHttpException($e->getMessage(), 0, $e);
		}
		if($loginEntity) {
			AuthHelper::setToken($token);
			$this->checkStatus($loginEntity);
			$loginEntity->hideAttributes(['assignments', 'password', 'security']);
			return $loginEntity;
		} else {
			$this->breakSession();
		}
	}
	
	/*public function checkOwnerId(BaseEntity $entity, $fieldName = 'user_id') {
		if($entity->{$fieldName} != \App::$domain->account->auth->identity->id) {
			throw new ForbiddenHttpException();
		}
	}*/
	
	public function authentication(string $login, string $password, string $ip = null) : LoginEntity {
		$model = new LoginForm;
		$model->login = $login;
		$model->password = $password;
		return $this->authenticationByForm($model, $ip);
	}
	
	
	public function oneSelf(Query $query = null) {
		return \App::$domain->account->user->oneSelf($query);
	}
	
	public function isGuest() : bool {
		return \App::$domain->account->user->isGuest();
	}
	
	public function getIdentity() {
		return \App::$domain->account->user->getIdentity();
	}
	
	public function login(IdentityInterface $loginEntity, bool $rememberMe = false) {
		return \App::$domain->account->user->login($loginEntity, $rememberMe);
	}
	
	public function logout() {
		return \App::$domain->account->user->logout();
	}
	
	public function denyAccess() {
		return \App::$domain->account->user->denyAccess();
	}
	
	public function loginRequired() {
		return \App::$domain->account->user->loginRequired();
	}
	
	public function breakSession() {
		return \App::$domain->account->user->breakSession();
	}
	
	private function authenticationByForm(LoginForm $model, string $ip = null) : LoginEntity {
		if(empty($ip)) {
			$ip = ClientHelper::ip();
		}
		Helper::validateForm($model);
		try {
			$loginEntity = $this->oneIdentityByLoginWithAssignments($model->login);
		} catch(NotFoundHttpException $e) {
			$this->badPasswordException();
		}
		$isValidPassword = \App::$domain->account->security->isValidPassword($loginEntity->id, $model->password);
		if(!$isValidPassword) {
			$this->badPasswordException();
		}
		$loginEntity->token = \App::$domain->account->token->forge($loginEntity->id, $ip);
		
		if(!$loginEntity instanceof IdentityInterface || empty($loginEntity->id)) {
			$this->badPasswordException();
		}
		$this->checkStatus($loginEntity);
		AuthHelper::setToken($loginEntity->token);
		
		$loginArray = $loginEntity->toArray();
		$loginArray['token'] = StringHelper::mask($loginArray['token']);
		$this->afterMethodTrigger(__METHOD__, [
			'login' => $model->login,
			'password' => StringHelper::mask($model->password, 0),
		], $loginArray);
		$loginEntity->hideAttributes(['assignments', 'password', 'security']);
		$event = new AccountAuthenticationEvent;
		$event->identity = $loginEntity;
		$event->login = $model->login;
		$this->trigger(AccountEventEnum::AUTHENTICATION, $event);
		return $loginEntity;
	}
	
	private function checkStatus(IdentityInterface $entity)
    {
        if (\App::$domain->account->login->isForbiddenByStatus($entity->status)) {
            throw new ServerErrorHttpException(Yii::t('account/login', 'user_status_forbidden'));
        }
    }
	
	private function oneIdentityByLoginWithAssignments($login) {
		$query = new Query;
		$query->with('assignments');
		$loginEntity = \App::$domain->account->login->oneByAny($login, $query);
		return $loginEntity;
	}
	
	private function badPasswordException() {
		$error = new ErrorCollection();
		$error->add('password', 'account/auth', 'incorrect_login_or_password');
		throw new UnprocessableEntityHttpException($error);
	}
}
