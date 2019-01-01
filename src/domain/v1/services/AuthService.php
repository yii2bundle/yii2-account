<?php

namespace yii2module\account\domain\v1\services;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\services\BaseService;
use yii2lab\extension\registry\helpers\Registry;
use yii2lab\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v1\forms\LoginForm;
use yii2module\account\domain\v1\interfaces\services\AuthInterface;
use yii2module\account\domain\v2\helpers\AuthHelper;
use yii2woop\generated\enums\SubjectType;
use yii\web\ServerErrorHttpException;
use yii2module\account\domain\v1\entities\LoginEntity;

/**
 * Class AuthService
 *
 * @package yii2module\account\domain\v1\services
 *
 * @property \yii2module\account\domain\v1\interfaces\repositories\AuthInterface $repository
 */
class AuthService extends BaseService implements AuthInterface {

    public $rememberExpire = TimeEnum::SECOND_PER_DAY * 30;

	public function authentication($login, $password) {
		$body = compact(['login', 'password']);
		$body = Helper::validateForm(LoginForm::class, $body);
		try {
			$user = $this->repository->authentication($body['login'], $body['password']);
		} catch(NotFoundHttpException $e) {
			$user = false;
		}
		
		if(empty($user)) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_login_or_password');
			throw new UnprocessableEntityHttpException($error);
		}
		
		$this->checkStatus($user);
		
		AuthHelper::setToken($user->token);
		$user->showToken();
		return $user;
	}
	
	private function checkStatus(LoginEntity $entity)
	{
	    if (\App::$domain->account->login->isForbiddenByStatus($entity->status)) {
	        throw new ServerErrorHttpException(Yii::t('account/login', 'user_status_forbidden'));
	    }
	}

	// todo: перенести псевдо-авторизацию в доменное имя "pseudoAuth" в пакет "yii2woop/common/domain/account..."
	/**
	 * @param string      $login
	 * @param string      $ip
	 * @param SubjectType $subjectType
	 * @param string|null $email
	 *
	 * @return LoginEntity
	 * @throws UnprocessableEntityHttpException
	 */
    public function pseudoAuthentication($login, $ip, $subjectType, $email = null) {
	    trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);
	    /** @var LoginEntity $user */
	    $user = $this->repository->pseudoAuthentication($login, $ip, $subjectType, $email);
		if(empty($user)) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_login_or_password');
			throw new UnprocessableEntityHttpException($error);
		}
		AuthHelper::setToken($user->token);
		$user->showToken();
		return $user;
	}
	
	public function pseudoAuthenticationWithParent($login, $ip, $email = null, $parentLogin = null) {
		/** @var LoginEntity $user */
    	$user = $this->repository->pseudoAuthenticationWithParent($login, $ip, SubjectType::USER_UNIDENT_PSEUDO, $email, $parentLogin);
		if(empty($user)) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_login_or_password');
			throw new UnprocessableEntityHttpException($error);
		}
		AuthHelper::setToken($user->token);
		$user->showToken();
		return $user;
	}

	public function authenticationFromWeb($login, $password, $rememberMe = false) {
		$user = $this->authentication($login, $password);
		if(empty($user)) {
			return null;
		}
		$duration = $rememberMe ? $this->rememberExpire : 0;
		Yii::$app->user->login($user, $duration);
	}
	
	public function authenticationByToken($token, $type = null) {
		AuthHelper::setToken($token);
		try {
			$user = $this->domain->repositories->login->oneByToken($token, $type);
		} catch(NotFoundHttpException $e) {
			throw new UnauthorizedHttpException();
		}
		if(empty($user)) {
			$this->breakSession();
		}
		
		$this->checkStatus($user);
		
		return $user;
	}
	
	public function logout() {
		Yii::$app->user->logout();
	}
	
	public function denyAccess() {
		if(Yii::$app->user->getIsGuest()) {
			Yii::$app->user->loginRequired();
		} else {
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
		}
	}
	
	public function isGuest() {
		return Yii::$app->user->isGuest;
	}
	
	public function breakSession() {
		if(APP == API) {
			throw  new UnauthorizedHttpException;
		} else {
			$this->logout();
			if(APP != CONSOLE) {
				Yii::$app->session->destroy();
				Yii::$app->response->cookies->removeAll();
				Yii::$app->user->loginRequired();
			}
		}
	}
	
	public function getIdentity() {
		$identity = Yii::$app->user->identity;
		if(empty($identity)) {
			throw new UnauthorizedHttpException();
		}
		return $identity;
	}
	
	/**
	 * @param      $action
	 * @param null $access
	 * @param null $param
	 *
	 * @return boolean
	 * @deprecated
	 */
	public function checkAccess($action, $access = null, $param = null) {
		return $this->domain->rbac->checkAccess($action, $access, $param);
	}
	
	public function getToken() {
		$token = AuthHelper::getTokenFromIdentity();
		if($token) {
			return $token;
		}
		$token = AuthHelper::getTokenString();
		if($token) {
			return $token;
		}
		return null;
	}
	
	/**
	 * @return array
	 * @deprecated
	 */
	public function getBalance() {
		if(empty(Yii::$app->user->identity->login)) {
			return [];
		}
		$balance = $this->domain->balance->oneSelf();
		return $balance;
	}
	
}
