<?php

namespace yii2bundle\account\domain\v3\enums;

use yii2rails\extension\enum\base\BaseEnum;

class AccountConfirmActionEnum extends BaseEnum
{

    const REGISTRATION = 'registration';
	const RESTORE_PASSWORD = 'restore-password';

}