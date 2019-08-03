<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii\web\NotFoundHttpException;
use yii2bundle\account\domain\v3\exceptions\ConfirmIncorrectCodeException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\common\exceptions\CreatedHttpExceptionException;
use yii2bundle\account\domain\v3\forms\restorePassword\UpdatePasswordForm;

/**
 * Interface RestorePasswordInterface
 * 
 * @package yii2bundle\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 */
interface RestorePasswordInterface {
	
	/**
	 * @param UpdatePasswordForm $model
	 *
	 * @throws CreatedHttpExceptionException
	 * @throws NotFoundHttpException
	 * @throws UnprocessableEntityHttpException
	 */
	public function requestCode(UpdatePasswordForm $model);
	
	/**
	 * @param UpdatePasswordForm $model
	 *
	 * @throws ConfirmIncorrectCodeException
	 * @throws NotFoundHttpException
	 * @throws UnprocessableEntityHttpException
	 */
	public function verifyCode(UpdatePasswordForm $model);
	
	/**
	 * @param UpdatePasswordForm $model
	 * @throws UnprocessableEntityHttpException
	 */
	public function setNewPassword(UpdatePasswordForm $model);
	
}
