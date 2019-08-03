<?php

namespace yii2bundle\account\domain\v3\exceptions;

use yii\base\Exception;

class ConfirmIncorrectCodeException extends Exception
{
	/**
	 * @return string the user-friendly name of this exception
	 */
	public function getName()
	{
		return 'Incorrect code';
	}
}