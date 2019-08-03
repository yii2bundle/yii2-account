<?php

namespace yii2bundle\account\domain\v3\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class TokenEntity
 * 
 * @package yii2bundle\account\domain\v3\entities
 * 
 * @property $user_id
 * @property $token
 * @property $type
 * @property $ip
 * @property $platform
 * @property $browser
 * @property $version
 * @property $created_at
 * @property $expire_at
 * @property $expire
 */
class TokenEntity extends BaseEntity {

	protected $user_id;
	protected $token;
	protected $type;
	protected $ip;
	protected $platform;
	protected $browser;
	protected $version;
	protected $created_at = TIMESTAMP;
	protected $expire_at;
	
	public function rules() {
		return [
			[['token', 'ip', 'platform', 'browser', 'version'], 'trim'],
			[['user_id', 'token', 'ip', 'created_at', 'expire_at'], 'required'],
			[['user_id', 'created_at', 'expire_at'], 'integer'],
			['expire_at', 'compare', 'compareAttribute' => 'created_at', 'operator' => '>'],
			['token', 'string', 'length' => [32, 255]],
		];
	}
	
}
