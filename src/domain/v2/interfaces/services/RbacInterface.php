<?php

namespace yii2bundle\account\domain\v2\interfaces\services;

interface RbacInterface {

	public function can($rule, $param = null, $allowCaching = true);

}