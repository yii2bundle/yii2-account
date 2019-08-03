<?php

namespace yii2bundle\account\module\helpers;

use yii2rails\extension\menu\base\BaseMenu;
use yii2rails\extension\menu\interfaces\MenuInterface;

class SecurityMenu extends BaseMenu implements MenuInterface {
	
	public function toArray() {
		$items = [
			[
				'url' => 'user/security/email',
				'label' => ['account/security', 'email'],
			],
			[
				'url' => 'user/security/password',
				'label' => ['account/security', 'password'],
			],
		];
		$items = $this->filter($items);
		return $items;
	}
	
}
