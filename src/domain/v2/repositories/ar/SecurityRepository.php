<?php

namespace yii2bundle\account\domain\v2\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2bundle\account\domain\v2\interfaces\repositories\SecurityInterface;
use yii2bundle\account\domain\v2\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveArRepository implements SecurityInterface {
	
	use SecurityTrait;
	
}