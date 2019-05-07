<?php

namespace yii2module\account\domain\v3\validators;

use Yii;
use yii\validators\StringValidator;
use yii2rails\extension\validator\BaseValidator;

class UserBirthdayValidator extends BaseValidator {

	public function validateAttribute($model, $attribute) {
        $birthdayDate = $model->getAttributes(['birthday_day', 'birthday_month', 'birthday_year']);
        $time = strtotime($birthdayDate['birthday_year'].'/'.$birthdayDate['birthday_month'].'/'.$birthdayDate['birthday_day']);
        $birthdayDate = date ('Y-m-d', $time);
        $todayDate = date('Y-m-d');
        if($todayDate < $birthdayDate){
            $this->addError($model, $attribute, Yii::t('account/main', 'bad_date'));
        }
	}
}
