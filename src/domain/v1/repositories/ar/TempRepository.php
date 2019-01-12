<?php

namespace yii2module\account\domain\v1\repositories\ar;

use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;

class TempRepository extends BaseActiveArRepository {
	
	protected $modelClass = 'yii2module\account\domain\v1\models\UserRegistration';
	protected $primaryKey = 'login';
	
	public function oneByLogin($login) {
		$model = $this->oneModelByCondition(['login' => $login]);
		return $this->forgeEntity($model);
	}
	
}