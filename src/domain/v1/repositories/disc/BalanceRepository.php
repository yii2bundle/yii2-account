<?php

namespace yii2module\account\domain\v1\repositories\disc;

use yii2lab\domain\data\Query;
use yii2lab\extension\arrayTools\repositories\base\BaseActiveDiscRepository;

class BalanceRepository extends BaseActiveDiscRepository {

	public $table = 'user';

	public function oneByLogin($login) {
		$query = Query::forge();
		$query->where('login', $login);
		$user = $this->one($query);
		return $this->forgeEntity($user->balance);
	}

}