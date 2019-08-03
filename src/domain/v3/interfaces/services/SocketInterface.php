<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii\base\ErrorException;
use yii2bundle\account\domain\v3\entities\SocketEventEntity;

/**
 * Interface SocketInterface
 * 
 * @package yii2bundle\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
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
