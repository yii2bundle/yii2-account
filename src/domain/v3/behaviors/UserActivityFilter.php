<?php

namespace yii2bundle\account\domain\v3\behaviors;

use yii\base\Behavior;
use yii2rails\domain\enums\EventEnum;
use yii2rails\domain\events\MethodEvent;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\domain\services\base\BaseService;

class UserActivityFilter extends Behavior {
	
	public $methods;
	
	public function events() {
		return [
			EventEnum::EVENT_AFTER_METHOD => 'prepare',
		];
	}
	
	public function prepare(MethodEvent $event) {
		if(!$this->isEnabledMethod($event->activeMethod)) {
			return;
		}
		/** @var BaseService|BaseRepository $sender */
		$sender = $event->sender;
		\App::$domain->account->activity->create([
			'domain' => $sender->domain->id,
			'service' => $sender->id,
			'method' => $event->activeMethod,
			'request' => $event->query,
			'response' => $event->content,
		]);
	}
	
	private function isEnabledMethod($activeMethod) {
		return empty($this->methods) || in_array($activeMethod, $this->methods);
	}
}
