<?php

namespace yii2module\account\domain\v3\strategies\token\handlers;

use yii2module\account\domain\v3\dto\TokenDto;

interface HandlerInterface {
	
	public function getIdentityId(TokenDto $tokenDto);
	
	public function forge($userId, $ip, $profile = null);
	
}
