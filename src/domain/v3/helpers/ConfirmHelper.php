<?php

namespace yii2bundle\account\domain\v3\helpers;

use Exception;

class ConfirmHelper  {
	
	const LENGTH_DEFAULT = 6;
	const LENGTH_MIN = 2;
	const LENGTH_MAX = 9;
	
	public static function generateCode($len = self::LENGTH_DEFAULT)
	{
		if($len < self::LENGTH_MIN) {
			throw new Exception('Length can not be less then 2');
		}
		if($len > self::LENGTH_MAX) {
			throw new Exception('Length can not be greater then 9');
		}
		$min = '1' . str_repeat('0', $len - 2) . '1';
		$max = str_repeat('9', $len);
		return rand($min, $max);
	}

}
