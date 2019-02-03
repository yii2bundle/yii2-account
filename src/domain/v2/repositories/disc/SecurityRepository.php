<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use yii2rails\extension\arrayTools\repositories\base\BaseActiveDiscRepository;
use yii2module\account\domain\v2\interfaces\repositories\SecurityInterface;

class SecurityRepository extends BaseActiveDiscRepository implements SecurityInterface {
	
	public $table = 'user_security';
	
	public function changePassword($password, $newPassword) {
		// TODO: Implement changePassword() method.
	}
	
	public function changeEmail($password, $email) {
		// TODO: Implement changeEmail() method.
	}
	
	public function generateUniqueToken() {
		// TODO: Implement generateUniqueToken() method.
	}
}