<?php

namespace yii2module\account\domain\v3\services;

use yii2module\account\domain\v3\strategies\token\handlers\JwtStrategy;
use yii2module\account\domain\v3\strategies\token\TokenContext;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2module\account\domain\v3\interfaces\services\TokenInterface;
use Exception;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\services\base\BaseService;
use yii2module\account\domain\v3\entities\TokenEntity;
use yii2module\account\domain\v3\helpers\TokenHelper;

class TokenService extends BaseService implements TokenInterface {

    public $tokenStrategyDefinitions = [
	    'jwt' => [
		    'class' => JwtStrategy::class,
		    'profile' => 'auth',
	    ],
    ];
	
	public function forge($userId, $ip, $expire = null) {
		$tokenCotext = new TokenContext;
		$tokenCotext->setStrategyDefinitions($this->tokenStrategyDefinitions);
		return $tokenCotext->forge($userId, $ip, $expire);
	}
    
    public function identityIdByToken(string $token) {
	    $tokenCotext = new TokenContext;
	    $tokenCotext->setStrategyDefinitions($this->tokenStrategyDefinitions);
	    $identityId = $tokenCotext->getIdentityId($token);
	    return $identityId;
    }

}
