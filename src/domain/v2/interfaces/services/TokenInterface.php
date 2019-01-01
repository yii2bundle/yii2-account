<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii2module\account\domain\v2\entities\TokenEntity;

/**
 * Interface TokenInterface
 * 
 * @package yii2module\account\domain\v2\interfaces\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\TokenInterface $repository
 */
interface TokenInterface {
	
	public function forge($userId, $ip, $expire = null);
	
	/**
	 * @param $token
	 * @param $ip
	 *
	 * @return null|TokenEntity
	 */
	public function validate($token, $ip);
	public function deleteAllExpired();
	public function deleteAll();
	public function deleteOneByToken($token);
	
}
