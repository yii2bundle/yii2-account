<?php

namespace yii2module\account\domain\v3\interfaces\services;

use yii2module\account\domain\v3\entities\TokenEntity;

/**
 * Interface TokenInterface
 * 
 * @package yii2module\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 * @property-read \yii2module\account\domain\v3\interfaces\repositories\TokenInterface $repository
 */
interface TokenInterface {

    /**
     * @param integer $userId
     * @param string $ip
     * @param null $expire
     * @return string
     */
    public function forge($userId, $ip, $expire = null);
	
	/**
	 * @param $token
	 * @param $ip
	 *
	 * @return null|TokenEntity
	 */
	public function validate($token, $ip);
	
}
