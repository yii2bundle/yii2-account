<?php

namespace yii2module\account\domain\v1\interfaces\repositories;

interface SecurityInterface {
	
	public function changePassword($password, $newPassword);
	public function changeEmail($password, $email);

}