<?php

namespace yii2bundle\account\domain\v3\filters\token;

use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\helpers\TokenHelper;

abstract class BaseTokenFilter extends BaseObject {

	public $type;
	
	/**
	 * @param $token
	 *
	 * @return LoginEntity
	 */
	abstract public function authByToken($token);
	
	/**
	 * @param $body
	 * @param $ip
	 *
	 * @return LoginEntity
	 */
	abstract public function login($body, $ip);

	protected function forgeToken($token) {
		if(empty($this->type)) {
			throw new InvalidArgumentException('Attribute "type" not defined in filter "' . static::class . '"!');
		}
		$tokenDto = TokenHelper::forgeDtoFromToken($token);
		return $this->type . SPC . $tokenDto->token;
	}
}
