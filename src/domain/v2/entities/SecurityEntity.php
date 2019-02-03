<?php

namespace yii2module\account\domain\v2\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class SecurityEntity
 *
 * @package yii2module\account\domain\v2\entities
 *
 * @property integer $id
 * @property string $email
 * @property string $token
 * @property string $password_hash
 */
class SecurityEntity extends BaseEntity {

	protected $id;
	protected $email;
	protected $token;
	protected $password_hash;
	
}
