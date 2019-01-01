<?php

namespace yii2module\account\domain\v1\services;

use yii2lab\domain\helpers\Helper;
use yii2module\account\domain\v1\forms\ChangeEmailForm;
use yii2module\account\domain\v1\forms\ChangePasswordForm;
use yii2lab\domain\services\BaseService;

class SecurityService extends BaseService {
	
	public function changeEmail($body) {
		$body = Helper::validateForm(ChangeEmailForm::class, $body);
		$this->repository->changeEmail($body['password'], $body['email']);
	}
	
	public function changePassword($body) {
		
		$body = Helper::validateForm(ChangePasswordForm::class, $body);
		$this->repository->changePassword($body['password'], $body['new_password']);
	}

}
