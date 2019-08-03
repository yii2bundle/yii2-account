<?php

namespace yii2bundle\account\domain\v3\entities;

use Workerman\Connection\ConnectionInterface;
use yii2rails\domain\BaseEntity;

/**
 * Class SocketConnectEntity
 * 
 * @package yii2bundle\account\domain\v3\entities
 *
 * @property $user_id
 * @property ConnectionInterface $connection
 * @property $data
 * @property string[] $events
 */
class SocketConnectEntity extends BaseEntity {

	protected $user_id;
    protected $connection;
    protected $data;
    protected $events;

}
