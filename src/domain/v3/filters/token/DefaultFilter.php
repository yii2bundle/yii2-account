<?php

namespace yii2module\account\domain\v3\filters\token;

use yii\web\IdentityInterface;
use yii2module\account\domain\v3\entities\LoginEntity;

class DefaultFilter extends BaseTokenFilter {
	
	public function authByToken($token) {
		$loginEntity = \App::$domain->account->repositories->login->oneByToken($token);
		return $loginEntity;
	}
	
	public function login($body, $ip) {
		$loginEntity = \App::$domain->account->repositories->auth->authentication($body['login'], $body['password'], $ip);
		if($loginEntity instanceof IdentityInterface) {
            $loginEntity->token = $this->forgeToken($loginEntity->token);
            return $loginEntity;
        }
		return null;
	}
	
}