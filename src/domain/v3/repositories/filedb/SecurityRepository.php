<?php

namespace yii2module\account\domain\v3\repositories\filedb;

use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2module\account\domain\v3\interfaces\repositories\SecurityInterface;
use yii2module\account\domain\v3\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveFiledbRepository implements SecurityInterface {
	
	use SecurityTrait;
	
}