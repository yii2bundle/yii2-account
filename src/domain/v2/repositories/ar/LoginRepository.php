<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v2\repositories\traits\LoginTrait;

class LoginRepository extends ActiveArRepository implements LoginInterface {

	protected $schemaClass = true;
	
	use LoginTrait;

}