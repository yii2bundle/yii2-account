<?php

namespace yii2module\account\domain\v1\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;

class ConfirmRepository extends ActiveArRepository {
	
	protected $modelClass = 'yii2module\account\domain\v1\models\UserConfirm';
	protected $primaryKey = false;
	
	public function uniqueFields() {
		return [
			['login', 'code'],
		];
	}
	
	public function oneByLoginAndAction($login, $action) {
		$model = $this->oneModelByCondition([
			'login' => $login,
			'action' => $action,
		]);
		return $this->forgeEntity($model);
	}
	
	public function oneByLogin($login) {
		$model = $this->oneModelByCondition(['login' => $login]);
		return $this->forgeEntity($model);
	}
	
	public function cleanOld($login, $action, $expire = 30) {
		$all = $this->model->find()->where([
			'login' => $login,
			'action' => $action,
		])->all();
		foreach($all as $model) {
			if(time() - strtotime($model->created_at) > $expire) {
				$model->delete();
			}
		}
	}
	
}
