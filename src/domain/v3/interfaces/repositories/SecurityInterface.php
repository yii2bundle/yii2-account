<?php

namespace yii2bundle\account\domain\v3\interfaces\repositories;

use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\interfaces\repositories\CrudInterface;
use yii2bundle\account\domain\v3\entities\SecurityEntity;

interface SecurityInterface extends CrudInterface {
	
	public function oneByIdentityId(int $identityId, Query $query = null) : SecurityEntity;
	
	/*
	 * @param string $token
	 * @param string $type
	 *
	 * @return SecurityEntity
	 */
	//public function oneByToken($token, $type = null);
	
	/**
	 * @param string $password
	 * @param string $newPassword
	 *
	 * @throws UnprocessableEntityHttpException
	 */
	//public function changePassword($password, $newPassword);
	
	/**
	 * @param string $password
	 * @param string $email
	 *
	 * @throws UnprocessableEntityHttpException
	 */
	//public function changeEmail($password, $email);
	
	/*
	 * @param integer $userId
	 * @param string $password
	 *
	 * @return SecurityEntity|false
	 * @throws UnprocessableEntityHttpException
	 * @throws NotFoundHttpException
	 */
	//public function validatePassword($userId, $password);
	
	/*
	 * @param $userId
	 *
	 * @return SecurityEntity
	 * @throws NotFoundHttpException
	 */
	//public function generateTokenById($userId);
	
}