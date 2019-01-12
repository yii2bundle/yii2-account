<?php

namespace yii2module\account\domain\v2\repositories\disc;

use yii2lab\domain\data\Query;
use yii2lab\extension\arrayTools\repositories\base\BaseActiveDiscRepository;
use yii2module\account\domain\v2\interfaces\repositories\TestInterface;

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