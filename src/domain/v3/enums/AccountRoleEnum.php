<?php

namespace yii2bundle\account\domain\v3\enums;

use yii2rails\extension\enum\base\BaseEnum;

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