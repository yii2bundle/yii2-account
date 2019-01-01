<?php
/**
 * WebUser
 */
namespace yii2module\account\domain\v1\web;

use yii\web\NotFoundHttpException;
use yii2module\account\domain\v1\entities\LoginEntity;
use yii2lab\extension\registry\helpers\Registry;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;
use yii\filters\auth\AuthMethod;
use yii\web\IdentityInterface;
use yii2module\account\domain\v2\helpers\AuthHelper;

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
	
	public function getIdentity($autoRenew = true)
	{
		$identity = parent::getIdentity($autoRenew);
		if(!empty($identity)) {
			return $identity;
		}
		$identity = $this->runAuthMethod();
		if(!empty($identity)) {
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
		$value = Yii::$app->getRequest()->getCookies()->getValue($this->identityCookie['name']);
		if ($value === null) {
			return null;
		}
		$data = json_decode($value, true);
		if (count($data) == 3) {
			list ($id, $authKey, $duration) = $data;
			try {
				$identity = \App::$domain->account->login->oneById($id);
			} catch(NotFoundHttpException $e) {
				$identity = null;
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
		$session = Yii::$app->getSession();
		$id = $session->getHasSessionId() || $session->getIsActive() ? $session->get($this->idParam) : null;

		if ($id === null) {
			$identity = null;
		} else {
			try {
				$identity = \App::$domain->account->login->oneById($id);
				AuthHelper::setToken(Yii::$app->session->get(AuthHelper::KEY));
			} catch(NotFoundHttpException $e) {
				$identity = null;
			}
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