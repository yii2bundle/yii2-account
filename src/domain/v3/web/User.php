<?php

namespace yii2module\account\domain\v3\web;

use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2module\account\domain\v3\entities\LoginEntity;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;
use yii\filters\auth\AuthMethod;
use yii\web\IdentityInterface;
use yii2module\account\domain\v3\helpers\AuthHelper;

/**
 * @property-read User $model
 * @property-read LoginEntity $identity
 */
class User extends \yii\web\User
{

	public $authMethod = [];

	public function init()
	{
		if ($this->enableAutoLogin && !isset($this->identityCookie['name'])) {
			throw new InvalidConfigException('User::identityCookie must contain the "name" element.');
		}
	}
	
	public function loginRequired($checkAjax = true, $checkAcceptHeader = true) {
		try {
			return parent::loginRequired($checkAjax, $checkAcceptHeader);
		} catch(ForbiddenHttpException $e) {
			throw new UnauthorizedHttpException();
		}
	}

	public function login(IdentityInterface $identity, $duration = 0)
	{
		if ($this->beforeLogin($identity, false, $duration)) {
			$this->switchIdentity($identity, $duration);
			$id = $identity->getId();
			if(APP != CONSOLE) {
				$ip = Yii::$app->getRequest()->getUserIP();
				if ($this->enableSession) {
					$log = "User '$id' logged in from $ip with duration $duration.";
				} else {
					$log = "User '$id' logged in from $ip. Session not enabled.";
				}
				
				$this->regenerateCsrfToken();
				
				Yii::info($log, __METHOD__);
			}
			$this->afterLogin($identity, false, $duration);
		}
		
		return !$this->getIsGuest();
	}
	
	public function getIdentity($autoRenew = true)
	{
		$identity = parent::getIdentity($autoRenew);
		if(!empty($identity) && !empty($identity->id)) {
			return $identity;
		}
		$identity = $this->runAuthMethod();
		if(!empty($identity) && !empty($identity->id)) {
			$this->setIdentity($identity);
		}
		return $identity;
	}

	public function loginByAccessToken($token, $type = null)
	{
		$identity = \App::$domain->account->auth->authenticationByToken($token, $type);
		if ($identity && $this->login($identity)) {
			return $identity;
		}
		return null;
	}

	protected function getIdentityAndDurationFromCookie()
	{
		$identity = null;
		$value = Yii::$app->getRequest()->getCookies()->getValue($this->identityCookie['name']);
		if ($value === null) {
			return null;
		}
		$data = json_decode($value, true);
		if (count($data) == 3) {
			list ($id, $authKey, $duration) = $data;
			if(!empty($id)) {
				try {
					$identity = \App::$domain->account->login->oneById($id);
				} catch(NotFoundHttpException $e) {}
			}
			if ($identity !== null) {
				if (!$identity instanceof IdentityInterface) {
					throw new InvalidValueException("Class::findIdentity() must return an object implementing IdentityInterface.");
				} elseif (!$identity->validateAuthKey($authKey)) {
					Yii::warning("Invalid auth key attempted for user '$id': $authKey", __METHOD__);
				} else {
					return ['identity' => $identity, 'duration' => $duration];
				}
			}
		}
		$this->removeIdentityCookie();
		return null;
	}

	protected function renewAuthStatus()
	{
		/** @var LoginEntity|null $identity */
		$identity = null;
		$session = Yii::$app->getSession();
		$id = $session->getHasSessionId() || $session->getIsActive() ? $session->get($this->idParam) : null;
		if (!empty($id)) {
			try {
				$identity = \App::$domain->account->login->oneById($id);
				AuthHelper::updateTokenViaSession();
			} catch(NotFoundHttpException $e) {}
		}

		$this->setIdentity($identity);

		if ($identity !== null && ($this->authTimeout !== null || $this->absoluteAuthTimeout !== null)) {
			$expire = $this->authTimeout !== null ? $session->get($this->authTimeoutParam) : null;
			$expireAbsolute = $this->absoluteAuthTimeout !== null ? $session->get($this->absoluteAuthTimeoutParam) : null;
			if ($expire !== null && $expire < time() || $expireAbsolute !== null && $expireAbsolute < time()) {
				$this->logout(false);
			} elseif ($this->authTimeout !== null) {
				$session->set($this->authTimeoutParam, time() + $this->authTimeout);
			}
		}

		if ($this->enableAutoLogin) {
			if ($this->getIsGuest()) {
				$this->loginByCookie();
			} elseif ($this->autoRenewCookie) {
				$this->renewIdentityCookie();
			}
		}
	}

	private function runAuthMethod() {
		foreach($this->authMethod as $methodClass) {
			/** @var AuthMethod $methodInstance */
			$methodInstance = new $methodClass;
			$identity = $methodInstance->authenticate($this, Yii::$app->request, null);
			if(!empty($identity)) {
				return $identity;
			}
		}
		return null;
	}

}