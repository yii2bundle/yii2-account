<?php

namespace yii2module\account\domain\v3\interfaces\repositories;

use yii2rails\domain\interfaces\repositories\CrudInterface;

interface TestInterface extends CrudInterface {
	
	public function getOneByRole($role);
	public function oneByLogin($login);

}