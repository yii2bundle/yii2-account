<?php

namespace yii2bundle\account\domain\v2\services;

use yii\web\NotFoundHttpException;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2bundle\account\domain\v2\forms\RestorePasswordForm;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\services\base\BaseService;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2bundle\account\domain\v2\interfaces\services\RestorePasswordInterface;

/**
 * Class RestorePasswordService
 *
 * @package yii2bundle\account\domain\v2\services
 *
 * @property-read \yii2bundle\account\domain\v2\interfaces\repositories\RestorePasswordInterface $repository
 * @property-read \yii2bundle\account\domain\v2\Domain $domain
 */
class RestorePasswordService extends BaseService implements RestorePasswordInterface {

    public $tokenExpire = TimeEnum::SECOND_PER_MINUTE * 1;

	public function request($login, $mail = null) {
		$body = compact(['login']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_REQUEST);
		$this->validateLogin($login);
		$this->repository->requestNewPassword($login, $mail);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact(['login', 'activation_code']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CHECK);
		$this->validateLogin($login);
		$this->verifyActivationCode($login, $activation_code);
	}
	
	public function confirm($login, $activation_code, $password) {
		$body = compact(['login', 'activation_code', 'password']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CONFIRM);
		$this->validateLogin($login);
		$this->verifyActivationCode($login, $activation_code);
		$this->repository->setNewPassword($login, $activation_code, $password);
	}
	
	protected function validateLogin($login) {
		$user = \App::$domain->account->login->isExistsByLogin($login);
		if(empty($user)) {
			$error = new ErrorCollection();
			$error->add('login', 'account/main', 'login_not_found');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	protected function verifyActivationCode($login, $activation_code) {
		try {
			$isChecked = $this->repository->checkActivationCode($login, $activation_code);
		} catch(NotFoundHttpException $e) {
			$error = new ErrorCollection();
			$error->add('login', 'account/restore-password', 'not_found_request');
			throw new UnprocessableEntityHttpException($error, 0, $e);
		}
		if(!$isChecked) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/restore-password', 'invalid_activation_code');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
}
