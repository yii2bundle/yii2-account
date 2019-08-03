<?php

namespace yii2bundle\account\domain\v3\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2bundle\account\domain\v3\interfaces\repositories\ActivityInterface;

/**
 * Class ActivityRepository
 * 
 * @package yii2bundle\account\domain\v3\repositories\ar
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 */
class ActivityRepository extends BaseActiveArRepository implements ActivityInterface {
	
	protected $modelClass = 'yii2bundle\account\domain\v3\models\UserActivity';

}
