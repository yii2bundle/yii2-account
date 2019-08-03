<?php

namespace yii2bundle\account\domain\v2\repositories\filedb;

use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2bundle\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2bundle\account\domain\v2\repositories\traits\LoginTrait;

class LoginRepository extends BaseActiveFiledbRepository implements LoginInterface {
	
	use LoginTrait;
	
	protected $schemaClass = true;
	
}