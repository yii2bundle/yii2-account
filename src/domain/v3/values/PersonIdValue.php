<?php

namespace yii2module\account\domain\v3\values;

use Yii;
use yii2rails\domain\exceptions\ReadOnlyException;
use yii2rails\domain\values\UserIdValue;

class PersonIdValue extends UserIdValue {

	public function get($default = null) {
		return \App::$domain->account->auth->identity->person_id;
	}

}
