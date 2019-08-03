<?php

namespace yii2bundle\account\domain\v3\exceptions;

use yii\base\Exception;

class InvalidIpAddressException extends Exception
{
	/**
	 * @return string the user-friendly name of this exception
	 */
	public function getName()
	{
		return 'Invalid IP address';
	}
}