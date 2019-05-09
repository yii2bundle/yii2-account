<?php

namespace yii2module\account\domain\v3\strategies\login\handlers;

use yii2module\account\domain\v3\entities\ContactEntity;

class EmailStrategy extends Base implements HandlerInterface {
	
	public function oneByLogin(string $email) {
		/** @var ContactEntity $contactEntity */
		$contactEntity = \App::$domain->account->contact->oneByData($email, 'email');
		return $contactEntity->identity_id;
	}
	
}
