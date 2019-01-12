<?php

namespace yii2module\account\domain\v1\services;

use Yii;
use yii\web\ForbiddenHttpException;
use yii2lab\domain\services\base\BaseService;
use yii2module\account\domain\v1\interfaces\services\RbacInterface;

class RbacService extends BaseService implements RbacInterface {
	
	public function can($rule, $param = null, $allowCaching = true) {
		if($this->repository->isGuestOnlyAllowed($rule)) {
			throw new ForbiddenHttpException();
		}
		if($this->repository->isAuthOnlyAllowed($rule)) {
			$this->domain->auth->breakSession();
		}
		if(!Yii::$app->user->can($rule, $param, $allowCaching)) {
			if(Yii::$app->user->isGuest) {
				$this->domain->auth->breakSession();
			}
			throw new ForbiddenHttpException();
		}
	}

}
