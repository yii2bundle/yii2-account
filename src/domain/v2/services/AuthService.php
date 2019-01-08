<?php

namespace yii2module\account\domain\v2\services;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\services\base\BaseService;
use yii2lab\domain\traits\MethodEventTrait;
use yii2lab\extension\common\helpers\StringHelper;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2lab\extension\web\helpers\ClientHelper;
use yii2lab\extension\yii\helpers\ArrayHelper;
use yii2module\account\domain\v2\behaviors\UserActivityFilter;
use yii2module\account\domain\v2\filters\token\BaseTokenFilter;
use yii2module\account\domain\v2\filters\token\DefaultFilter;
use yii2module\account\domain\v2\forms\LoginForm;
use yii2module\account\domain\v2\helpers\AuthHelper;
use yii2module\account\domain\v2\helpers\TokenHelper;
use yii2module\account\domain\v2\interfaces\services\AuthInterface;
use yii\web\ServerErrorHttpException;
use yii2module\account\domain\v2\entities\LoginEntity;

/**
 * Class AuthService
 *
 * @package yii2module\account\domain\v2\services
 *
 * @property \yii2module\account\domain\v2\interfaces\repositories\AuthInterface $repository
 */
class AuthService extends BaseService implements AuthInterface {

	use MethodEventTrait;
	
    public $rememberExpire = TimeEnum::SECOND_PER_DAY * 30;
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
	
	public function authentication2($body, $ip = null) {
		if(empty($ip)) {
			$ip = ClientHelper::ip();
		}
		$body = Helper::validateForm(LoginForm::class, $body);
		try {
			
			$loginEntity = TokenHelper::login($body, $ip, $this->tokenAuthMethods);
			
			/*$type = !empty($type) ? $type : ArrayHelper::firstKey($this->tokenAuthMethods);
			$definitionFilter = ArrayHelper::getValue($this->tokenAuthMethods, $type);
			if(!$definitionFilter) {
				$error = new ErrorCollection();
				$error->add('tokenType', 'account/auth', 'token_type_not_found');
				throw new UnprocessableEntityHttpException($error);
			}
			// @var BaseTokenFilter $filterInstance
			$filterInstance = Yii::createObject($definitionFilter);
			$filterInstance->type = $type;
			$loginEntity = $filterInstance->login($body, $ip);*/
			
		} catch(NotFoundHttpException $e) {
			$loginEntity = false;
		} catch(InvalidArgumentException $e) {
			$error = new ErrorCollection();
			$error->add('password', $e->getMessage());
			throw new UnprocessableEntityHttpException($error);
		}
		if(!$loginEntity instanceof IdentityInterface || empty($loginEntity->id)) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_login_or_password');
			throw new UnprocessableEntityHttpException($error);
		}
		$this->checkStatus($loginEntity);
		AuthHelper::setToken($loginEntity->token);
		
		$loginArray = $loginEntity->toArray();
		$loginArray['token'] = StringHelper::mask($loginArray['token']);
		$this->afterMethodTrigger('authentication', [
			'login' => $body['login'],
			'password' => StringHelper::mask($body['password'], 0),
		], $loginArray);
		
		return $loginEntity;
	}
	
	public function authentication($login, $password, $ip = null) {
		if(empty($ip)) {
			$ip = ClientHelper::ip();
		}
		$body = compact(['login', 'password']);
		$body = Helper::validateForm(LoginForm::class, $body);
		try {
			$loginEntity = $this->repository->authentication($body['login'], $body['password'], $ip);
		} catch(NotFoundHttpException $e) {
			$loginEntity = false;
		}
		if(!$loginEntity instanceof IdentityInterface || empty($loginEntity->id)) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_login_or_password');
			throw new UnprocessableEntityHttpException($error);
		}
		$this->checkStatus($loginEntity);
		AuthHelper::setToken($loginEntity->token);
		
		$loginArray = $loginEntity->toArray();
		$loginArray['token'] = StringHelper::mask($loginArray['token']);
		$this->afterMethodTrigger(__METHOD__, [
			'login' => $login,
			'password' => StringHelper::mask($password, 0),
		], $loginArray);
		
		return $loginEntity;
	}
	
	private function checkStatus(IdentityInterface $entity)
	{
	    if (\App::$domain->account->login->isForbiddenByStatus($entity->status)) {
	        throw new ServerErrorHttpException(Yii::t('account/login', 'user_status_forbidden'));
	    }
	}

	public function getIdentity() {
		if(Yii::$app->user->isGuest) {
            $this->breakSession();

		}
		return Yii::$app->user->identity;
	}

	public function authenticationFromWeb($login, $password, $rememberMe = false) {
		$loginEntity = $this->authentication($login, $password);
		$this->login($loginEntity, $rememberMe);
	}

	public function login(IdentityInterface $loginEntity, $rememberMe = false) {
        if(empty($loginEntity)) {
            return null;
        }
        $duration = $rememberMe ? $this->rememberExpire : 0;
        Yii::$app->user->login($loginEntity, $duration);
        AuthHelper::setToken($loginEntity->token);
    }

	public function authenticationByToken($token, $type = null) {
		if(empty($token)) {
			throw new InvalidArgumentException('Empty token');
		}
		try {
            $loginEntity = TokenHelper::authByToken($token, $this->tokenAuthMethods);
			//AuthHelper::setToken($loginEntity->token);
		} catch(NotFoundHttpException $e) {
			throw new UnauthorizedHttpException();
		}
		if(empty($loginEntity)) {
			$this->breakSession();
		}
		$this->checkStatus($loginEntity);
		return $loginEntity;
	}
	
	public function logout() {
		Yii::$app->user->logout();
		AuthHelper::setToken('');
	}
	
	public function denyAccess() {
		if(Yii::$app->user->getIsGuest()) {
			$this->breakSession();
		} else {
			throw new ForbiddenHttpException();
		}
	}
	
	public function loginRequired() {
		try {
			Yii::$app->user->loginRequired();
		} catch(InvalidConfigException $e) {
			return;
		}
	}
	
	public function breakSession() {
		if(APP == CONSOLE) {
			return;
		}
		if(APP == API) {
			throw new UnauthorizedHttpException;
		} else {
			$this->logout();
			Yii::$app->session->destroy();
			Yii::$app->response->cookies->removeAll();
			$this->loginRequired();
		}
	}
	
	public function checkOwnerId(BaseEntity $entity, $fieldName = 'user_id') {
		if($entity->{$fieldName} != \App::$domain->account->auth->identity->id) {
			throw new ForbiddenHttpException();
		}
	}
	
}
