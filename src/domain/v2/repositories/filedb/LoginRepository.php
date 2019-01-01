<?php

namespace yii2module\account\domain\v2\repositories\filedb;

use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2lab\domain\repositories\ActiveFiledbRepository;
use yii2module\account\domain\v2\repositories\traits\LoginTrait;

class LoginRepository extends ActiveFiledbRepository implements LoginInterface {
	
	use LoginTrait;
	
	protected $schemaClass = true;
	
}