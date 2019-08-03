<?php

namespace yii2bundle\account\domain\v3\entities;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;

/**
 * Class ActivityEntity
 * 
 * @package yii2bundle\account\domain\v3\entities
 * 
 * @property $id
 * @property $user_id
 * @property $domain
 * @property $service
 * @property $method
 * @property $request
 * @property $response
 * @property $platform
 * @property $browser
 * @property $version
 * @property $created_at
 */
class ActivityEntity extends BaseEntity {

	protected $id;
	protected $user_id;
	protected $domain;
	protected $service;
	protected $method;
	protected $request;
	protected $response;
	protected $platform;
	protected $browser;
	protected $version;
	protected $created_at;
	
	public function fieldType() {
		return [
			'id' => 'integer',
			'created_at' => TimeValue::class,
		];
	}
	
	public function behaviors() {
		return [
			[
				'class' => BlameableBehavior::class,
				'attributes' => [
					self::EVENT_INIT => ['user_id'],
				],
			],
			[
				'class' => TimestampBehavior::class,
				'attributes' => [
					self::EVENT_INIT => ['created_at'],
				],
			],
		];
	}
	
}
