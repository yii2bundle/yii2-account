<?php

namespace yii2module\account\domain\v3\repositories\ar;

use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v3\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveArRepository {
	
	use SecurityTrait;
	
    public function tableName() {
        return 'user_login';
    }

    public function fieldAlias() {
        return [
            'token' => 'remember_token',
            'password_hash' => 'password',
        ];
    }

}
