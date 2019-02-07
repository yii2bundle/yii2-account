<?php

namespace yii2module\account\domain\v3\repositories\filedb;

use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2module\account\domain\v3\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v3\repositories\traits\LoginTrait;

class LoginRepository extends BaseActiveFiledbRepository implements LoginInterface {
	
	use LoginTrait;
	
	protected $schemaClass = true;
	
}