<?php

namespace yii2module\account\domain\v3\strategies\login;

use yii2module\account\domain\v3\helpers\LoginTypeHelper;
use yii2module\account\domain\v3\strategies\login\handlers\EmailStrategy;
use yii2module\account\domain\v3\strategies\login\handlers\LoginStrategy;
use yii2module\account\domain\v3\strategies\login\handlers\PhoneStrategy;
use yii2module\account\domain\v3\strategies\login\handlers\TokenStrategy;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\dto\WithDto;
use yii2rails\domain\entities\relation\RelationEntity;
use yii2rails\domain\enums\RelationEnum;
use yii2rails\extension\scenario\base\BaseStrategyContextHandlers;
use yii2module\account\domain\v3\strategies\login\handlers\One;
use yii2module\account\domain\v3\strategies\login\handlers\Many;
use yii2module\account\domain\v3\strategies\login\handlers\ManyToMany;
use yii2module\account\domain\v3\strategies\login\handlers\HandlerInterface;

/**
 * Class PaymentStrategy
 *
 * @package yii2rails\domain\strategies\payment
 *
 * @property-read HandlerInterface $strategyInstance
 */
class LoginContext extends BaseStrategyContextHandlers {
	
	public function getStrategyDefinitions() {
		return [
			'login' => LoginStrategy::class,
			'phone' => PhoneStrategy::class,
			'email' => EmailStrategy::class,
			'token' => TokenStrategy::class,
		];
	}
	
	public function getLoginId(string $login, Query $query = null) {
		$loginType = $this->getLoginType($login);
		$this->strategyName = $loginType;
		return $this->strategyInstance->oneByLogin($login, $query);
	}
	
	private function getLoginType(string $login) {
		if(LoginTypeHelper::isPhone($login)) {
			return 'phone';
		} elseif(LoginTypeHelper::isEmail($login)) {
			return 'email';
		} elseif(LoginTypeHelper::isToken($login)) {
			return 'token';
		} else {
			return 'login';
		}
	}
}