<?php

namespace yii2bundle\account\domain\v3\filters\login;

use Yii;
use yii\web\NotFoundHttpException;

use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2bundle\account\domain\v3\interfaces\LoginValidatorInterface;

class LoginPhoneValidator implements LoginValidatorInterface {
	
	public $allowCountriesId;
	
	public function normalize($value) : string {
		try {
			$phoneInfoEntity = \App::$domain->geo->phone->parse($value);
			return $phoneInfoEntity->id;
		} catch(NotFoundHttpException $e) {
			$error = new ErrorCollection;
			$error->add('login', Yii::t('account/login', 'not_valid'));
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	public function isValid($value) : bool {
		try {
			$phoneEntity = \App::$domain->geo->phone->oneByPhone($value);
		} catch(NotFoundHttpException $e) {
			return false;
		}
		if(isset($this->allowCountriesId)) {
			if(in_array($phoneEntity->country_id, $this->allowCountriesId)) {
				return false;
			}
		}
		return true;
	}
	
}
