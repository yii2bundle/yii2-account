<?php

namespace yii2module\account\domain\v2\repositories\core;

use yii2lab\extension\core\domain\repositories\base\BaseActiveCoreRepository;
use yii2module\account\domain\v2\interfaces\repositories\SecurityInterface;

class SecurityRepository extends BaseActiveCoreRepository implements SecurityInterface {
	
	public $point = 'security';
	
	public function changePassword($password, $newPassword) {
		$response = $this->put('password', [
			'password' => $password,
			'new_password' => $newPassword,
		]);
	}
	
	public function changeEmail($password, $email) {
		$response = $this->put('email', [
			'password' => $password,
			'email' => $email,
		]);
	}
	
}