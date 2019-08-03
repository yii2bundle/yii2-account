<?php

namespace yii2bundle\account\domain\v2\repositories\test;

use yii2bundle\account\domain\v2\interfaces\repositories\RestorePasswordInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class RestorePasswordRepository
 *
 * @package yii2bundle\account\domain\v2\repositories\test
 *
 * @deprecated
 */
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
	
}