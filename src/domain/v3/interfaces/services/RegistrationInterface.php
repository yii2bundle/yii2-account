<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii\web\NotFoundHttpException;
use yii2bundle\account\domain\v3\exceptions\ConfirmIncorrectCodeException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yii2rails\extension\common\exceptions\CreatedHttpExceptionException;
use yii2bundle\account\domain\v3\forms\registration\PersonInfoForm;

/**
 * Interface Registration
 * 
 * @package yii2bundle\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 */
interface RegistrationInterface {

    /**
     * @param requestCodeWithPersonInfo $model
     *
     * @throws CreatedHttpExceptionException
     * @throws UnprocessableEntityHttpException
     * @throws AlreadyExistsException
     */
    public function requestCodeWithPersonInfo(PersonInfoForm $model);

	/**
	 * @param PersonInfoForm $model
	 *
	 * @throws CreatedHttpExceptionException
	 * @throws UnprocessableEntityHttpException
	 * @throws AlreadyExistsException
	 */
	public function requestCode(PersonInfoForm $model);
	
	/**
	 * @param PersonInfoForm $model
	 *
	 * @throws ConfirmIncorrectCodeException
	 * @throws NotFoundHttpException
	 * @throws UnprocessableEntityHttpException
	 * @throws AlreadyExistsException
	 */
	public function verifyCode(PersonInfoForm $model);
	
	/**
	 * @param PersonInfoForm $model
	 *
	 * @throws UnprocessableEntityHttpException
	 */
	public function createAccountWeb(PersonInfoForm $model);

}
