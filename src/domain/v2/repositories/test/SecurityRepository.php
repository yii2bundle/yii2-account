<?php

namespace yii2bundle\account\domain\v2\repositories\test;

use yii\web\NotFoundHttpException;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2bundle\account\domain\v2\entities\SecurityEntity;
use yii2bundle\account\domain\v2\interfaces\repositories\SecurityInterface;

class SecurityRepository extends BaseRepository implements SecurityInterface {

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