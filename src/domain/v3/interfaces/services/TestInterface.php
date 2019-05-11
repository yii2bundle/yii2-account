<?php

namespace yii2module\account\domain\v3\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

interface TestInterface extends CrudInterface {
	
	//public function getOneByRole($role);
	public function oneByLogin($login);
	
}