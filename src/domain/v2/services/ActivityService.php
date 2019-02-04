<?php

namespace yii2module\account\domain\v2\services;

use yii2rails\extension\web\helpers\ClientHelper;
use yii2rails\extension\yii\helpers\ArrayHelper;
use yii2module\account\domain\v2\interfaces\services\ActivityInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ActivityService
 * 
 * @package yii2module\account\domain\v2\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\ActivityInterface $repository
 */
class ActivityService extends BaseActiveService implements ActivityInterface {

	public $sources;
	
	public function create($data) {
		if(!self::isEnabled($data)) {
			return null;
		}
		$agent = ClientHelper::getAgentInfo();
		$data['platform'] = ArrayHelper::getValue($agent, 'platform');
		$data['browser'] = ArrayHelper::getValue($agent, 'browser');
		$data['version'] = ArrayHelper::getValue($agent, 'version');
		return parent::create($data);
	}
	
	private function isEnabled($data) {
		if(empty($this->sources)) {
			return false;
		}
		$map = [
			$data['domain'],
			$data['domain'] . DOT . $data['service'],
			$data['domain'] . DOT . $data['service'] . DOT . $data['method'],
		];
		foreach($map as $name) {
			if(in_array($name, $this->sources)) {
				return true;
			}
		}
		return false;
	}
	
}
