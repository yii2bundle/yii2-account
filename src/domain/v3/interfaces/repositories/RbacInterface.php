<?php

namespace yii2module\account\domain\v3\interfaces\repositories;

interface RbacInterface {
	
	public function isGuestOnlyAllowed($rule);
	public function isAuthOnlyAllowed($rule);

}