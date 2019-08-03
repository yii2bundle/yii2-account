<?php

namespace yii2bundle\account\domain\v3\interfaces\repositories;

use yii2rails\domain\interfaces\repositories\CrudInterface;
use yii2bundle\account\domain\v3\entities\TokenEntity;

/**
 * Interface TokenInterface
 * 
 * @package yii2bundle\account\domain\v3\interfaces\repositories
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 */
interface TokenInterface extends CrudInterface {
	
	/**
	 * @param $token
	 *
	 * @return TokenEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneByToken($token);
	
	/**
	 * @param $ip
	 *
	 * @return TokenEntity[]
	 */
	public function allByIp($ip);
	public function allByUserId($userId);
	//public function deleteByIp($ip);
	public function deleteOneByToken($token);
	//public function deleteAllExpiredByIp($ip);
	public function deleteAllExpired();
	
}
