<?php

namespace yii2bundle\account\domain\v3\interfaces\repositories;

use yii2rails\domain\interfaces\repositories\CrudInterface;

interface TestInterface extends CrudInterface {
	
	public function getOneByRole($role);
	public function oneByLogin($login);

}