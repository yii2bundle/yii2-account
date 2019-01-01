<?php

namespace yii2module\account\domain\v1\repositories\core;

use yii2module\account\domain\v1\interfaces\repositories\RegistrationInterface;
use yii2woop\common\domain\account\v1\repositories\tps\RegistrationRepository as TpsRegistrationRepository;

class RegistrationRepository extends TpsRegistrationRepository implements RegistrationInterface {

}