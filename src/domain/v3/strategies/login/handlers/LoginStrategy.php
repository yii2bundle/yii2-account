<?php

namespace yii2bundle\account\domain\v3\strategies\login\handlers;

use yii2rails\domain\data\Query;

class LoginStrategy implements HandlerInterface {
	
	public function identityIdByAny(string $login) {
		$loginEntity = \App::$domain->account->repositories->identity->oneByLogin($login);
		return $loginEntity->id;
	}
	
}
