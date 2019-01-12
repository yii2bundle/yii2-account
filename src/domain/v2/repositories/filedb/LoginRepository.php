<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use yii2lab\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v2\repositories\traits\LoginTrait;

class LoginRepository extends BaseActiveFiledbRepository implements LoginInterface {
	
	use LoginTrait;
	
	protected $schemaClass = true;
	
}