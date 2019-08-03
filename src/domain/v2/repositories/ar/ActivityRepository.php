<?php

namespace yii2bundle\account\domain\v2\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2bundle\account\domain\v2\interfaces\repositories\ActivityInterface;

/**
 * Class ActivityRepository
 * 
 * @package yii2bundle\account\domain\v2\repositories\ar
 * 
 * @property-read \yii2bundle\account\domain\v2\Domain $domain
 */
class ActivityRepository extends BaseActiveArRepository implements ActivityInterface {
	
	protected $modelClass = 'yii2bundle\account\domain\v2\models\UserActivity';

}
