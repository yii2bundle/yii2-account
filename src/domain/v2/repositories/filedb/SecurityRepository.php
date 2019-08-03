<?php

namespace yii2bundle\account\domain\v2\repositories\filedb;

use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2bundle\account\domain\v2\interfaces\repositories\SecurityInterface;
use yii2bundle\account\domain\v2\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveFiledbRepository implements SecurityInterface {
	
	use SecurityTrait;
	
}