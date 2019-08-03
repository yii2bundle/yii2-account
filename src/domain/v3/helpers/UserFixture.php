<?php

namespace yii2bundle\account\domain\v3\helpers;

use Yii;

class UserFixture {

    const PASSWORD = 'Wwwqqq111';
	const PASSWORD_HASH = '$2y$13$mSWZ77XG5XRwPBJCf5QcIew3dr1nhRZfj/AQHPc0QAONMSbx.2oF2';

	public static function generate($user) {
		$user['username'] = isset($user['username']) ? $user['username'] : $user['login'];
		$user['status'] = isset($user['status']) ? $user['status'] : 10;
		$user['email'] = isset($user['email']) ? $user['email'] : $user['login'] . '@ya.ru';
		$user['role'] = isset($user['role']) ? $user['role'] : 'rUnknownUser';
		if(!empty($user['password'])) {
			$user['password_hash'] = Yii::$app->security->generatePasswordHash($user['password']);
		}
		$user['password_hash'] = isset($user['password_hash']) ? $user['password_hash'] : Yii::$app->security->generatePasswordHash(self::PASSWORD);
		$user['auth_key'] = md5($user['id'] . $user['login'] . $user['role']);
		$user['password_reset_token'] = null;
		$user['created_at'] = 1497349018;
		$user['updated_at'] = 1497349018;
		return $user;
	}

	public static function generateAll($all) {
		foreach($all as &$user) {
			$user = self::generate($user);
		}
		return $all;
	}
	
}
