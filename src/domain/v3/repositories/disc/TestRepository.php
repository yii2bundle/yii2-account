<?php

namespace yii2module\account\domain\v3\repositories\disc;

use yii2rails\domain\data\Query;
use yii2rails\extension\arrayTools\repositories\base\BaseActiveDiscRepository;
use yii2module\account\domain\v3\interfaces\repositories\TestInterface;

class TestRepository extends BaseActiveDiscRepository implements TestInterface {
	
	public $table = 'user';

	public function getOneByRole($role) {
		$query = Query::forge();
		$query->where('role', $role);
		return $this->one($query);
	}

	public function oneByLogin($login) {
		$query = Query::forge();
		$query->where('login', $login);
		return $this->one($query);
	}

}