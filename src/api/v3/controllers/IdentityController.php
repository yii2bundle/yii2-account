<?php

namespace yii2module\account\api\v3\controllers;

use yii2lab\applicationTemplate\common\enums\ApplicationPermissionEnum;
use yii2rails\extension\web\helpers\Behavior;
use yii2lab\rest\domain\rest\ActiveControllerWithQuery as Controller;

class IdentityController extends Controller
{

	public $service = 'account.identity';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'cors' => Behavior::cors(),
			'authenticator' => Behavior::auth(),
            'access' => Behavior::access(ApplicationPermissionEnum::BACKEND_ALL),
		];
	}

}