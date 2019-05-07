<?php

namespace yii2module\account\domain\v3\services\core;

use yii2rails\extension\core\domain\repositories\base\BaseCoreRepository;
use yii2rails\extension\core\domain\services\base\BaseCoreService;
use yii2rails\domain\helpers\Helper;
use yii2module\account\domain\v3\exceptions\ConfirmAlreadyExistsException;
use yii2module\account\domain\v3\forms\registration\PersonInfoForm;
use yii2module\account\domain\v3\forms\RegistrationForm;
use yii2module\account\domain\v3\interfaces\services\RegistrationInterface;

/**
 * Class RegistrationService
 *
 * @package yii2module\account\domain\v3\services\core
 *
 * @property-read BaseCoreRepository $repository
 */
class RegistrationService extends BaseCoreService implements RegistrationInterface {
	
	public $point = 'registration';
	public $version = 1;

    public function requestCodeWithPersonInfo(PersonInfoForm $model) {
        $model->scenario = PersonInfoForm::SCENARIO_PERSON_INFO;
        if(!$model->validate()) {
            throw new UnprocessableEntityHttpException($model);
        }
        $response = $this->repository->post('request-activation-code', $model->toArray());
        if($response->status_code == 202) {
            throw new ConfirmAlreadyExistsException();
        }
    }

    public function requestCode(PersonInfoForm $model) {
        $model->scenario = PersonInfoForm::SCENARIO_REQUEST_CODE;
        if(!$model->validate()) {
            throw new UnprocessableEntityHttpException($model);
        }
        $response = $this->repository->post('request-activation-code', $model->toArray());
        if($response->status_code == 202) {
            throw new ConfirmAlreadyExistsException();
        }
    }

    public function verifyCode(PersonInfoForm $model) {
        $model->scenario = PersonInfoForm::SCENARIO_VERIFY_CODE;
        if(!$model->validate()) {
            throw new UnprocessableEntityHttpException($model);
        }
        $this->repository->post('verify-activation-code', $model->toArray());
    }

    public function createAccountWeb(PersonInfoForm $model) {
        $model->scenario = PersonInfoForm::SCENARIO_CREATE_ACCOUNT;
        if(!$model->validate()) {
            throw new UnprocessableEntityHttpException($model);
        }
        $data = $model->toArray();
        $data['birthday'] = $data['birthday_year'] . '-' . $data['birthday_month'] . '-' . $data['birthday_day'];
        $this->repository->post('create-account', $data);
    }

}
