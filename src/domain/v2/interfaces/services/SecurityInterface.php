<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii2lab\domain\interfaces\services\CrudInterface;

interface SecurityInterface extends CrudInterface {
	
	public function changeEmail($body);
	public function changePassword($body);

}