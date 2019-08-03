<?php

namespace yii2bundle\account\domain\v2\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

interface TestInterface extends CrudInterface {
	
	public function getOneByRole($role);
	public function oneByLogin($login);
	
}