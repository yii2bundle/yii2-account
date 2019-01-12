<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v2\interfaces\repositories\SecurityInterface;
use yii2module\account\domain\v2\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveArRepository implements SecurityInterface {
	
	use SecurityTrait;
	
}