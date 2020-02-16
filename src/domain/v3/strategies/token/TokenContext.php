<?php

namespace yii2bundle\account\domain\v3\strategies\token;

use yii\base\InvalidArgumentException;
use yii2bundle\account\domain\v3\helpers\TokenHelper;
use yii2bundle\account\domain\v3\strategies\token\handlers\EmailStrategy;
use yii2bundle\account\domain\v3\strategies\token\handlers\LoginStrategy;
use yii2bundle\account\domain\v3\strategies\token\handlers\Many;
use yii2bundle\account\domain\v3\strategies\token\handlers\ManyToMany;
use yii2bundle\account\domain\v3\strategies\token\handlers\One;
use yii2bundle\account\domain\v3\strategies\token\handlers\PhoneStrategy;
use yii2bundle\account\domain\v3\strategies\token\handlers\TokenStrategy;
use yii2rails\extension\scenario\base\BaseStrategyContextHandlers;

class TokenContext extends BaseStrategyContextHandlers
{

    /*public function getStrategyDefinitions() {
        return [
            'jwt' => [
                'class' => JwtStrategy::class,
                'profile' => 'auth',
            ],
        ];
    }*/

    public function getFirstStrategyName()
    {
        $definition = $this->getStrategyDefinitions();
        reset($definition);
        $firstKey = key($definition);
        return $firstKey;
    }

    public function forge($userId, $ip, $expire = null)
    {
        $firstKey = $this->getFirstStrategyName();
        /** @var \yii2bundle\account\domain\v3\strategies\token\handlers\HandlerInterface $strategyInstance */
        $strategyInstance = $this->forgeStrategyInstanceByName($firstKey);
        return $strategyInstance->forge($userId, $ip, $expire);
    }

    public function getIdentityId(string $token)
    {
        $tokenDto = TokenHelper::forgeDtoFromToken($token);
        if ( ! $this->isValidType($tokenDto->type)) {
            throw new InvalidArgumentException('Invalid token type!');
        }
        /** @var \yii2bundle\account\domain\v3\strategies\token\handlers\HandlerInterface $strategyInstance */
        $strategyInstance = $this->forgeStrategyInstanceByName($tokenDto->type);
        return $strategyInstance->getIdentityId($tokenDto);
    }

    private function isValidType(string $type)
    {
        $definitions = $this->getStrategyDefinitions();
        return array_key_exists($type, $definitions);
    }

}