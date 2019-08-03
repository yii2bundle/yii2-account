<?php

namespace yii2bundle\account\domain\v3\strategies\login\handlers;

use yii2rails\domain\data\Query;

interface HandlerInterface {
	
	public function identityIdByAny(string $login);
	
}
