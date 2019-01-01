<?php

namespace yii2module\account\domain\v1\interfaces\services;

interface RbacInterface {

	public function can($rule, $param = null, $allowCaching = true);

}