<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii\web\NotFoundHttpException;

/**
 * Class RestorePasswordRepository
 *
 * @package yii2module\account\domain\v2\repositories\ar
 *
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
interface RestorePasswordInterface {
	
	public function requestNewPassword($login, $mail = null);
	
	/**
	 * @param $login
	 * @param $code
	 *
	 * @return bool
	 *
	 * @throws NotFoundHttpException
	 */
	
	public function checkActivationCode($login, $code);
	public function setNewPassword($login, $code, $password);

}