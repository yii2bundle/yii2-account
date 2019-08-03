<?php

namespace yii2bundle\account\domain\v3\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class ContactEntity
 * 
 * @package yii2bundle\account\domain\v3\entities
 * 
 * @property $id
 * @property $identity_id
 * @property $type
 * @property $data
 * @property $is_main
 */
class ContactEntity extends BaseEntity {

	protected $id;
	protected $identity_id;
	protected $type;
	protected $data;
	protected $is_main;

}
