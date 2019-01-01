<?php

namespace yii2module\account\domain\v2\exceptions;

use yii\base\Exception;

class NotFoundLoginException extends Exception
{
	/**
	 * @return string the user-friendly name of this exception
	 */
	public function getName()
	{
		return 'Login not found';
	}
}