<?php

namespace yii2module\account\domain\v3\strategies\login\handlers;

use yii2rails\domain\data\Query;

interface HandlerInterface {
	
	public function oneByLogin(string $phone);
	
}
