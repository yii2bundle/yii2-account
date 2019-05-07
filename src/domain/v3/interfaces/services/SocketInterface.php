<?php

namespace yii2module\account\domain\v3\interfaces\services;

use yii\base\ErrorException;
use yii2module\account\domain\v3\entities\SocketEventEntity;

/**
 * Interface SocketInterface
 * 
 * @package yii2module\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 */
interface SocketInterface {

    /**
     * @param SocketEventEntity $event
     * @return mixed
     * @throws ErrorException
     */
    public function sendMessage(SocketEventEntity $event);
    public function startServer();
	
}
