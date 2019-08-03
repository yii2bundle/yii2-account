<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

interface TestInterface extends CrudInterface {
	
	public function oneByLogin($login);
	
}