<?php

namespace yii2module\account\domain\v3\events;

use yii\base\Event;

class AccountAuthenticationEvent extends Event
{

    public $identity;
    public $login;

}