<?php

namespace yii2bundle\account\domain\v3\strategies\login\handlers;

use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2bundle\account\domain\v3\helpers\AuthHelper;
use yii2bundle\account\domain\v3\helpers\TokenHelper;
use yii2bundle\account\domain\v3\strategies\token\TokenContext;
use yii2rails\domain\data\Query;

class TokenStrategy implements HandlerInterface {
	
	public function identityIdByAny(string $token) {
		$identityId = \App::$domain->account->token->identityIdByToken($token);
		//$tokenCotext = new TokenContext;
		//$identityId = $tokenCotext->getIdentityId($token);
		try {
			return $identityId;
			//$loginEntity = \App::$domain->account->identity->oneById($identityId);
			//$loginEntity = TokenHelper::authByToken($token, \App::$domain->account->auth->tokenAuthMethods);
		} catch(NotFoundHttpException $e) {
			throw new UnauthorizedHttpException();
		}
	}
	
}
