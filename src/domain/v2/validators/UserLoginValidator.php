<?php

namespace yii2module\account\domain\v2\validators;

use Yii;
use yii\validators\RegularExpressionValidator;
use yii2rails\extension\common\enums\RegexpPatternEnum;
use yii2rails\extension\validator\BaseValidator;

class UserLoginValidator extends BaseValidator {

	public function validateAttribute($model, $attribute) {
        $model->$attribute = trim($model->$attribute);
        $validator = Yii::createObject([
            'class' => RegularExpressionValidator::class,
            'pattern' => RegexpPatternEnum::LOGIN_REQUIRED,
        ]);
        $validator->validateAttribute($model, $attribute);
        /*$validator = Yii::createObject([
            'class' => RegularExpressionValidator::class,
            'pattern' => '/^[a-zA-Z]$/',
        ]);
        $validator->validateAttribute($model, $attribute);*/
	}

}
