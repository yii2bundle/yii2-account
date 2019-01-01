<?php

namespace yii2module\account\domain\v2\repositories\ar;

use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v2\interfaces\repositories\ActivityInterface;

/**
 * Class ActivityRepository
 * 
 * @package yii2module\account\domain\v2\repositories\ar
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
class ActivityRepository extends BaseActiveArRepository implements ActivityInterface {
	
	protected $modelClass = 'yii2module\account\domain\v2\models\UserActivity';

}
