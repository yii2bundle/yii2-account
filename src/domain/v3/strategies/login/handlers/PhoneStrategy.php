<?php

namespace yii2module\account\domain\v3\strategies\login\handlers;

use yii2module\account\domain\v3\entities\ContactEntity;

class PhoneStrategy extends BaseContactStrategy implements HandlerInterface {
	
	protected $type = 'phone';
	
}
