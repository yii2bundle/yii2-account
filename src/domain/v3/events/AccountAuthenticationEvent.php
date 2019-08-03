<?php

namespace yii2bundle\account\domain\v3\events;

use yii\base\Event;

class AccountAuthenticationEvent extends Event
{

    public $identity;
    public $login;

}