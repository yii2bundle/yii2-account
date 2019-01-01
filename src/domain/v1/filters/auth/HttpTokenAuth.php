<?php

namespace yii2module\account\domain\v1\filters\auth;

use yii\filters\auth\AuthMethod;
use yii2module\account\domain\v2\helpers\AuthHelper;

class HttpTokenAuth extends AuthMethod
{
	/**
	 * @var string the HTTP authentication realm
	 */
	public $realm = 'api';


	/**
	 * @inheritdoc
	 */
	public function authenticate($user, $request, $response)
	{
		$token = AuthHelper::getTokenFromQuery();
		if ($token !== null) {
			$identity = $user->loginByAccessToken($token, get_class($this));
			if ($identity === null) {
				$this->handleFailure($response);
			}
			return $identity;
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function challenge($response)
	{
		$response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
	}
}
