<?php

namespace yii2module\account\domain\v3\strategies\login\handlers;

use yii2module\account\domain\v3\entities\ContactEntity;

class BaseContactStrategy implements HandlerInterface {
	
	protected $type;
	
	public function identityIdByAny(string $login) {
		/** @var ContactEntity $contactEntity */
		$contactEntity = \App::$domain->account->contact->oneByData($login, $this->type);
		return $contactEntity->identity_id;
	}
	
}
