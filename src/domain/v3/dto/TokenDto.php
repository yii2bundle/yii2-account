<?php

namespace yii2bundle\account\domain\v3\dto;

use yii2rails\domain\base\BaseDto;

/**
 * Class TokenDto
 *
 * @package yii2bundle\account\domain\v3\dto
 *
 * @property $token
 * @property $type
 * @property $identity_id
 */
class TokenDto extends BaseDto {
	
	public $token;
	public $type;
	public $identity_id;
	
	public function getTokenString() {
		if(empty($this->token)) {
			return null;
		}
		if(empty($this->type)) {
			return $this->token;
		}
		return $this->type . SPC . $this->token;
	}
	
}
