<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;
use yii2module\account\domain\v2\interfaces\repositories\SecurityInterface;
use yii2module\account\domain\v2\repositories\traits\SecurityTrait;

class SecurityRepository extends ActiveArRepository implements SecurityInterface {
	
	use SecurityTrait;
	
}