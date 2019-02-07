<?php

namespace yii2module\account\domain\v3\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v3\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v3\repositories\traits\LoginTrait;

class LoginRepository extends BaseActiveArRepository implements LoginInterface {

	protected $schemaClass = true;
	
	use LoginTrait;

}