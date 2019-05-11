<?php

namespace yii2module\account\domain\v2\services;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2rails\domain\helpers\Helper;
use yii2rails\domain\services\base\BaseService;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v2\entities\ConfirmEntity;
use yii2module\account\domain\v2\enums\AccountConfirmActionEnum;
use yii2module\account\domain\v2\exceptions\ConfirmIncorrectCodeException;
use yii2module\account\domain\v2\helpers\LoginHelper;
use Yii;
use yii2module\account\domain\v2\forms\RegistrationForm;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v2\interfaces\services\RegistrationInterface;

class RegistrationService extends BaseService implements RegistrationInterface {

	public $expire = TimeEnum::SECOND_PER_MINUTE * 1;
	public $requiredEmail = false;
	
	private function validateLogin($login) {
		if(!\App::$domain->account->login->isValidLogin($login)) {
			$error = new ErrorCollection();
			$error->add('login', 'account/login', 'not_valid');
			throw new UnprocessableEntityHttpException($error);
		}
		$login = \App::$domain->account->login->normalizeLogin($login);
		return $login;
	}
	
	//todo: изменить путь чтения временного аккаунта для ригистрации. Инкапсулировать все в ядро. Сейчас запрос идет на прямую.
	public function createTempAccount($login, $email = null) {
		$login = $this->validateLogin($login);
		$body = compact(['login', 'email']);
		$scenario = RegistrationForm::SCENARIO_REQUEST;
		if($this->requiredEmail) {
			$scenario = RegistrationForm::SCENARIO_REQUEST_WITH_EMAIL;
		}
        Helper::validateForm(RegistrationForm::class, $body, $scenario);
		$this->checkLoginExistsInTps($login);
		\App::$domain->account->confirm->send($login, AccountConfirmActionEnum::REGISTRATION, $this->expire, ArrayHelper::toArray($body));
		/*try {
		
		} catch(ConfirmAlreadyExistsException $e) {
			$error = new ErrorCollection();
			$error->add('login', 'account/confirm', 'already_sended_code {phone}', ['phone' => LoginHelper::format($login)]);
			throw new UnprocessableEntityHttpException($error);
		}*/
	}
	
	public function checkActivationCode($login, $activation_code) {
		$login = $this->validateLogin($login);
		$body = compact(['login', 'activation_code']);
        Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CHECK);
		$this->verifyActivationCode($login, $activation_code);
	}
	
	public function activateAccount($login, $activation_code) {
		$login = $this->validateLogin($login);
		$this->checkActivationCode($login, $activation_code);
		\App::$domain->account->confirm->activate($login, AccountConfirmActionEnum::REGISTRATION, $activation_code);
	}
	
	public function createTpsAccount($login, $activation_code, $password, $email = null) {
		$login = $this->validateLogin($login);
		$body = compact(['login', 'activation_code', 'password']);
        Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CONFIRM);
		//$this->activateAccount($login, $activation_code);
		
		/** @var ConfirmEntity $confirmEntity */
		$confirmEntity = $this->verifyActivationCode($login, $activation_code);
		
		if(empty($email)) {
			$email = $confirmEntity->data['email'];
		}
		if(empty($email)) {
			$email = 'demo@example.com';
		}
		
		$data = compact('login','password','email');
		\App::$domain->account->login->create($data);
		\App::$domain->account->confirm->delete($login, AccountConfirmActionEnum::REGISTRATION);
	}

	private function checkLoginExistsInTps($login) {
		$login = LoginHelper::pregMatchLogin($login);
		/** @var LoginInterface $loginRepository */
		$loginRepository = \App::$domain->account->repositories->identity;
		$isExists = $loginRepository->isExistsByLogin($login);
		if($isExists) {
			$error = new ErrorCollection();
			$error->add('login', 'account/registration', 'user_already_exists_and_activated');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	private function verifyActivationCode($login, $activation_code) {
		$login = LoginHelper::pregMatchLogin($login);
		try {
			return \App::$domain->account->confirm->verifyCode($login, AccountConfirmActionEnum::REGISTRATION, $activation_code);
		} catch(ConfirmIncorrectCodeException $e) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/confirm', 'incorrect_code');
			throw new UnprocessableEntityHttpException($error, 0, $e);
		} catch(NotFoundHttpException $e) {
			$error = new ErrorCollection();
			$error->add('login', 'account/registration', 'temp_user_not_found');
			throw new UnprocessableEntityHttpException($error, 0, $e);
		}
	}
	
}
