<?php

namespace yii2module\account\domain\v3\interfaces;

interface LoginValidatorInterface {
	
	public function normalize($value) : string;
	public function isValid($value) : bool;
	
}
