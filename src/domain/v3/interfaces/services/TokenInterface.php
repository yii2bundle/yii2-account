<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii2bundle\account\domain\v3\entities\TokenEntity;

/**
 * Interface TokenInterface
 * 
 * @package yii2bundle\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 * @property-read \yii2bundle\account\domain\v3\interfaces\repositories\TokenInterface $repository
 */
interface TokenInterface {
	
	public function identityIdByToken(string $token);
	
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
	//public function validate($token, $ip);
	
}
