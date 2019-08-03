<?php

namespace yii2bundle\account\domain\v3\repositories\filedb;

use yii2bundle\account\domain\v3\interfaces\repositories\ContactInterface;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;

/**
 * Class ContactRepository
 * 
 * @package yii2bundle\account\domain\v3\repositories\ar
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 */
class ContactRepository extends BaseActiveFiledbRepository implements ContactInterface {
	
	protected $schemaClass = true;
	
	public function tableName()
	{
		return 'user_contact';
	}

}
