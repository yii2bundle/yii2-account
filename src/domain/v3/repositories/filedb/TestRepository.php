<?php

namespace yii2bundle\account\domain\v3\repositories\filedb;

use yii2rails\domain\data\Query;
use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2bundle\account\domain\v3\interfaces\repositories\TestInterface;

class TestRepository extends BaseActiveFiledbRepository implements TestInterface {
	
	public function tableName()
	{
		return 'user';
	}
	
	public function fieldAlias() {
		return [
			'name' => 'username',
			'token' => 'auth_key',
		];
	}
	
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