<?php

namespace yii2module\account\domain\v3\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class SecurityEntity
 *
 * @package yii2module\account\domain\v3\entities
 *
 * @property integer $id
 * @property string $login_id
 * @property string $password_hash
 * @property-write $password
 */
class SecurityEntity extends BaseEntity {

	protected $id;
	protected $login_id;
	protected $password_hash;
	
	public function setPassword($password) {
		$this->password_hash = \Yii::$app->security->generatePasswordHash($password);
	}
	
	public function isValidPassword($password) {
		return \Yii::$app->security->validatePassword($password, $this->password_hash);
	}

	
}
