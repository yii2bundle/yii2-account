<?php

namespace yii2module\account\domain\v1\entities;

use yii2lab\domain\BaseEntity;

class TempEntity extends BaseEntity {

	protected $login;
	protected $email;
	protected $activation_code;
	protected $ip;
	protected $created_at;
	protected $password;
	
}
