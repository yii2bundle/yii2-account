<?php

namespace yii2bundle\account\domain\v3\strategies\login\handlers;

use yii2bundle\account\domain\v3\entities\ContactEntity;

class EmailStrategy extends BaseContactStrategy implements HandlerInterface {
	
	protected $type = 'email';
	
}
