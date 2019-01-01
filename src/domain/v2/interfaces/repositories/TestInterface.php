<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii2lab\domain\interfaces\repositories\CrudInterface;

interface TestInterface extends CrudInterface {
	
	public function getOneByRole($role);
	public function oneByLogin($login);

}