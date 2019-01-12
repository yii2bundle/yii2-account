<?php

namespace yii2module\account\domain\v1\repositories\filedb;

use yii2lab\domain\data\Query;
use yii2lab\extension\filedb\repositories\base\BaseActiveFiledbRepository;

class TestRepository extends BaseActiveFiledbRepository {
	
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