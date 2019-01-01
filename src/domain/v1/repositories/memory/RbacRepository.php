<?php

namespace yii2module\account\domain\v1\repositories\memory;

use yii\helpers\ArrayHelper;
use yii2lab\domain\repositories\BaseRepository;
use Yii;

class RbacRepository extends BaseRepository {
	
	public function isGuestOnlyAllowed($rule) {
		return $this->isInRules('?', $rule) && !Yii::$app->user->isGuest;
	}

	public function isAuthOnlyAllowed($rule) {
		return $this->isInRules('@', $rule) && Yii::$app->user->isGuest;
	}

	private function isInRules($name, $rules) {
		return in_array($name, ArrayHelper::toArray($rules));
	}
	
}