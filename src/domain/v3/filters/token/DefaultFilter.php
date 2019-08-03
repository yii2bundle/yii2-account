<?php

namespace yii2bundle\account\domain\v3\filters\token;

use yii\web\IdentityInterface;
use yii2rails\domain\data\Query;
use yii2bundle\account\domain\v3\entities\LoginEntity;

class DefaultFilter extends BaseTokenFilter {
	
	public function authByToken($token) {
	    $query = new Query;
	    $query->with('assignments');
		$loginEntity = \App::$domain->account->repositories->identity->oneByToken($token, $query);
		return $loginEntity;
	}
	
	public function login($body, $ip) {
		$loginEntity = \App::$domain->account->auth->authentication($body['login'], $body['password'], $ip);
		if($loginEntity instanceof IdentityInterface) {
            $loginEntity->token = $this->forgeToken($loginEntity->token);
            return $loginEntity;
        }
		return null;
	}
	
}
