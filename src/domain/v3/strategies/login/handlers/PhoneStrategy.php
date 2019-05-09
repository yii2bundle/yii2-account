<?php

namespace yii2module\account\domain\v3\strategies\login\handlers;

use yii\web\NotFoundHttpException;
use yii2module\account\domain\v3\entities\ContactEntity;

class PhoneStrategy extends Base implements HandlerInterface {
	
	public function oneByLogin(string $phone) {
		/*if(\App::$domain->has('user')) {
			$personEntity = \App::$domain->user->person->oneByPhone($login);
			$query = new Query;
			$query->where(['person_id' => $personEntity->id]);
			$loginEntity = $this->one($query);
			return $loginEntity->id;
		}*/
		/** @var ContactEntity $contactEntity */
		$contactEntity = \App::$domain->account->contact->oneByData($phone, 'phone');
		return $contactEntity->identity_id;
	}
	
}
