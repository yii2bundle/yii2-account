<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use yii2lab\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2module\account\domain\v2\interfaces\repositories\SecurityInterface;
use yii2module\account\domain\v2\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveFiledbRepository implements SecurityInterface {
	
	use SecurityTrait;
	
}