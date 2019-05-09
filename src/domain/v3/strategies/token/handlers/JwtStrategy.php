<?php

namespace yii2module\account\domain\v3\strategies\token\handlers;

use yii2module\account\domain\v3\dto\TokenDto;
use yii2rails\domain\data\Query;

class JwtStrategy extends Base implements HandlerInterface {
	
	public $profile;
	
	public function getIdentityId(TokenDto $tokenDto) {
		$tokenEntity = \App::$domain->jwt->token->decode($tokenDto->token, $this->profile);
		return $tokenEntity->subject['id'];
	}
	
	public function forge($userId, $ip, $profile = null) {
		$subject = [
			'id' => $userId,
		];
		$profile = $profile ? $profile : $this->profile;
		$tokenEntity = \App::$domain->jwt->token->forgeBySubject($subject, $profile);
		return 'jwt '  . $tokenEntity->token;
	}
	
}
