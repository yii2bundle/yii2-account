<?php

namespace yii2module\account\domain\v1\repositories\test;

use yii2lab\domain\repositories\BaseRepository;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

class SecurityRepository extends BaseRepository {

	const PASSWORD = 'Wwwqqq111';

	public function changePassword($password, $newPassword) {
		if($password != self::PASSWORD) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_password');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	public function changeEmail($password, $email) {
		if($password != self::PASSWORD) {
			$error = new ErrorCollection();
			$error->add('password', 'account/auth', 'incorrect_password');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
}