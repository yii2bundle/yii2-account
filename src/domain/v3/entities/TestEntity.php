<?php

namespace yii2bundle\account\domain\v3\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class TestEntity
 *
 * @package yii2bundle\account\domain\v3\entities
 *
 * @property $id integer
 * @property $login string
 * @property $username string
 * @property $email string
 * @property $role string
 * @property $status integer
 */
class TestEntity extends BaseEntity {

	protected $id;
	protected $login;
	protected $username;
	protected $email;
	protected $role;
	protected $status;

}
