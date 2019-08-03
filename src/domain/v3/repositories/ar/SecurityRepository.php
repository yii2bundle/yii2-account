<?php

namespace yii2bundle\account\domain\v3\repositories\ar;

use yii2lab\db\domain\helpers\TableHelper;
use yii2bundle\account\domain\v3\entities\SecurityEntity;
use yii2bundle\account\domain\v3\interfaces\repositories\SecurityInterface;
use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2bundle\account\domain\v3\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveArRepository implements SecurityInterface {
	
	use SecurityTrait;
	
    public function tableName() {
        return 'user_security';
    }
	
}
