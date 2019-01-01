<?php

namespace yii2module\account\domain\v2\services\core;

use yii2lab\extension\core\domain\repositories\base\BaseCoreRepository;
use yii2lab\extension\core\domain\services\base\BaseCoreService;
use yii2lab\domain\helpers\Helper;
use yii2module\account\domain\v2\exceptions\ConfirmAlreadyExistsException;
use yii2module\account\domain\v2\forms\RegistrationForm;
use yii2module\account\domain\v2\interfaces\services\RegistrationInterface;

/**
 * Class RegistrationService
 *
 * @package yii2module\account\domain\v2\services\core
 *
 * @property-read BaseCoreRepository $repository
 */
class RegistrationService extends BaseCoreService implements RegistrationInterface {
	
	public $point = 'registration';
	public $version = 1;
	public $requiredEmail = false;
	
	public function createTempAccount($login, $email = null) {
		$body = compact('login', 'email');
		$scenario = RegistrationForm::SCENARIO_REQUEST;
		if($this->requiredEmail) {
			$scenario = RegistrationForm::SCENARIO_REQUEST_WITH_EMAIL;
		}
		Helper::validateForm(RegistrationForm::class, $body, $scenario);
		$response = $this->repository->post('create-account', $body);
		if($response->status_code == 202) {
			throw new ConfirmAlreadyExistsException();
		}
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact('login', 'activation_code');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CHECK);
		$this->repository->post('activate-account', $body);
	}
	
	public function activateAccount($login, $activation_code) {
		$body = compact('login', 'activation_code');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CHECK);
		$this->repository->post('activate-account', $body);
	}
	
	public function createTpsAccount($login, $activation_code, $password, $email = null) {
		$body = compact('login', 'activation_code', 'password');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CONFIRM);
		$this->repository->post('set-password', $body);
	}

}
