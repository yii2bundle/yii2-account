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
	
    public $rememberExpire = TimeEnum::SECOND_PER_DAY * 30;
    public $tokenAuthMethods = [
	    'bearer' => DefaultFilter::class,
    ];
	private $_identity = null;

	public function behaviors() {
		return [
			[
				'class' => UserActivityFilter::class,
				'methods' => ['authentication'],
			],
		];
	}

	public function oneSelf(Query $query = null) {
        $query = Query::forge($query);
        return \App::$domain->account->login->oneById($this->getIdentity()->id, $query);
    }
	
	public function isGuest() : bool {
		return Yii::$app->user->isGuest;
	}
 
	public function getIdentity() {
	    if(isset(Yii::$app->user)) {
            if(Yii::$app->user->isGuest) {
                $this->breakSession();
            }
            return Yii::$app->user->identity;
        }
        if($this->_identity === null) {
            $this->breakSession();
        }
        return $this->_identity;
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

	public function login(IdentityInterface $loginEntity, $rememberMe = false) {
        if(empty($loginEntity)) {
            return null;
        }
        $duration = $rememberMe ? $this->rememberExpire : 0;
        if(isset(Yii::$app->user)) {
            Yii::$app->user->login($loginEntity, $duration);
        }
        $this->_identity = $loginEntity;
        AuthHelper::setToken($loginEntity->token);
    }

	public function authenticationByToken($token, $type = null) {
        $loginEntity = $this->repository->authenticationByToken($token, $type);
		if(empty($loginEntity)) {
			$this->breakSession();
		}
		$this->checkStatus($loginEntity);
        $loginEntity->hideAttributes(['assignments', 'password', 'security']);
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

    private function checkStatus(IdentityInterface $entity)
    {
        if (\App::$domain->account->login->isForbiddenByStatus($entity->status)) {
            throw new ServerErrorHttpException(Yii::t('account/login', 'user_status_forbidden'));
        }
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
        $loginEntity->hideAttributes(['assignments', 'password', 'security']);
        $event = new AccountAuthenticationEvent;
        $event->identity = $loginEntity;
        $event->login = $login;
        $this->trigger(AccountEventEnum::AUTHENTICATION, $event);
        return $loginEntity;
    }

}
