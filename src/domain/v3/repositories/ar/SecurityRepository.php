<?php

namespace yii2module\account\domain\v3\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v3\interfaces\repositories\SecurityInterface;
use yii2module\account\domain\v3\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveArRepository implements SecurityInterface {
	
	use SecurityTrait;
	
}