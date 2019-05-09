<?php

namespace yii2module\account\domain\v3\repositories\ar;

use yii2lab\db\domain\helpers\TableHelper;
use yii2module\account\domain\v3\entities\SecurityEntity;
use yii2module\account\domain\v3\interfaces\repositories\SecurityInterface;
use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v3\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveArRepository implements SecurityInterface {
	
	use SecurityTrait;
	
    public function tableName() {
        return 'user_security';
    }
    
    public function oneByLoginId($loginId, Query $query = null) : SecurityEntity {
	    $query = new Query;
	    $query->andWhere(['identity_id' => $loginId]);
	    $securityEntity = $this->one($query);
	    return $securityEntity;
    }
	
}
