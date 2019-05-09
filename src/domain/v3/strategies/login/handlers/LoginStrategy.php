<?php

namespace yii2module\account\domain\v3\strategies\login\handlers;

use yii2rails\domain\data\Query;

class LoginStrategy extends Base implements HandlerInterface {
	
	public function oneByLogin(string $login) {
		$loginEntity = \App::$domain->account->repositories->identity->oneByLogin($login);
		return $loginEntity->id;
	}
	
}
