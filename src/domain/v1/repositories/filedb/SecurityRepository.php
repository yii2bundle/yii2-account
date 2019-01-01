<?php

namespace yii2module\account\domain\v1\repositories\filedb;

use yii2lab\domain\repositories\ActiveFiledbRepository;
use yii2module\account\domain\v2\interfaces\repositories\SecurityInterface;
use yii2module\account\domain\v2\repositories\traits\SecurityTrait;

class SecurityRepository extends ActiveFiledbRepository implements SecurityInterface {
	
	use SecurityTrait;
	
}