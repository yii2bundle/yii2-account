<?php

namespace yii2module\account\domain\v2\filters\auth;

use yii\filters\auth\AuthMethod;
use yii\web\Request;
use yii\web\Response;
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
		/** @var Request $request */
		$token = AuthHelper::getTokenFromQuery();
		if ($token) {
			$identity = \App::$domain->account->auth->authenticationByToken($token, get_class($this));
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
		/** @var Response $response */
		$response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
	}
}
