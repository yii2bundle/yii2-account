<?php

namespace yii2module\account\domain\v1\validators;

use Yii;
use yii2module\account\domain\v1\helpers\LoginHelper;
use yii\validators\Validator;

class LoginValidator extends Validator
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('account/registration', 'login_not_valid');
        }
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
	    $valid = LoginHelper::validate($value);
        return $valid ? null : [$this->message, []];
    }
	
}
