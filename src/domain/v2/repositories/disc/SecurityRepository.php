<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use yii2lab\domain\repositories\ActiveDiscRepository;
use yii2module\account\domain\v2\interfaces\repositories\SecurityInterface;

class SecurityRepository extends ActiveDiscRepository implements SecurityInterface {
	
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