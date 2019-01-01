<?php

namespace yii2module\account\domain\v1\repositories\test;

use yii2module\account\domain\v1\interfaces\repositories\RestorePasswordInterface;
use yii2lab\domain\repositories\BaseRepository;

class RestorePasswordRepository extends BaseRepository implements RestorePasswordInterface {

	public function requestNewPassword($login, $mail = null) {
	
	}
	
	public function checkActivationCode($login, $code) {
		if($code == 123456) {
			return true;
		}
		return false;
	}
	
	public function setNewPassword($login, $code, $password) {
	
	}
	
	public function isExists($login) {
		return $this->domain->repositories->test->isExists(['login' => $login]);
	}
	
}