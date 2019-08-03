<?php

namespace yii2bundle\account\domain\v3\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class SocketEventEntity
 * 
 * @package yii2bundle\account\domain\v3\entities
 *
 * @property $name
 * @property $user_id
 * @property $data
 */
class SocketEventEntity extends BaseEntity {

    protected $name;
	protected $user_id;
    protected $data;

}
