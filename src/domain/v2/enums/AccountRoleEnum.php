<?php

namespace yii2module\account\domain\v2\enums;

use yii2lab\extension\enum\base\BaseEnum;

class AccountRoleEnum extends BaseEnum
{

    // Администратор системы
    const ADMINISTRATOR = 'rAdministrator';

    // Идентифицированный пользователь
    const USER = 'rUser';

    // Гость системы
    const GUEST = 'rGuest';

    // Не идентифицированный пользователь
    const UNKNOWN_USER = 'rUnknownUser';

    // Корневой администратор системы
    const ROOT = 'rRoot';

    // Модератор системы
    const MODERATOR = 'rModerator';

    // Разработчик
    const DEVELOPER = 'rDeveloper';

}