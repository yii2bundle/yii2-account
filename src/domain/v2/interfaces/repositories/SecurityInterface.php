<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii\web\NotFoundHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\interfaces\repositories\CrudInterface;
use yii2module\account\domain\v2\entities\SecurityEntity;

interface SecurityInterface extends CrudInterface {
	
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
	public function changePassword($password, $newPassword);
	
	/**
	 * @param string $password
	 * @param string $email
	 *
	 * @throws UnprocessableEntityHttpException
	 */
	public function changeEmail($password, $email);
	
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