<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii\web\NotFoundHttpException;
use yii2lab\domain\interfaces\services\CrudInterface;
use yii2module\account\domain\v2\exceptions\ConfirmIncorrectCodeException;

interface ConfirmInterface extends CrudInterface {
	
	public function delete($login, $action);
	
	public function activate($login, $action, $code);
	
	public function isActivated($login, $action);
	
	/**
	 * @param $login
	 * @param $action
	 * @param $code
	 *
	 * @return bool
	 *
	 * @throws NotFoundHttpException
	 */
	public function isVerifyCode($login, $action, $code);
	
	/**
	 * @param $login
	 * @param $action
	 * @param $code
	 *
	 * @return mixed
	 * @throws ConfirmIncorrectCodeException
	 */
	public function verifyCode($login, $action, $code);
	
	public function isHas($login, $action);
	
	//public function oneByLoginAndAction($login, $action);
	public function send($login, $action, $expire, $data = null);

}