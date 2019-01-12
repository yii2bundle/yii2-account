<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v2\repositories\traits\LoginTrait;

class LoginRepository extends BaseActiveArRepository implements LoginInterface {

	protected $schemaClass = true;
	
	use LoginTrait;

}