<?php

namespace yii2bundle\account\domain\v2\repositories\tps;

use yii2bundle\account\domain\v2\interfaces\repositories\TokenInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class TokenRepository
 * 
 * @package yii2bundle\account\domain\v2\repositories\tps
 * 
 * @property-read \yii2bundle\account\domain\v2\Domain $domain
 */
class TokenRepository extends BaseRepository implements TokenInterface {

	protected $schemaClass;

}
