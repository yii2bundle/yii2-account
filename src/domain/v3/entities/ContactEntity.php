<?php

namespace yii2module\account\domain\v3\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class ContactEntity
 * 
 * @package yii2module\account\domain\v3\entities
 * 
 * @property $id
 * @property $login_id
 * @property $type
 * @property $data
 * @property $is_main
 */
class ContactEntity extends BaseEntity {

	protected $id;
	protected $login_id;
	protected $type;
	protected $data;
	protected $is_main;

}
