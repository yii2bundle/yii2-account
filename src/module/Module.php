<?php

namespace yii2module\account\module;

use Yii;
use yii\base\Module as YiiModule;
use yii\web\NotFoundHttpException;

/**
 * user module definition class
 */
class Module extends YiiModule
{
	
	public function beforeAction($action)
	{
		$controllerId = Yii::$app->controller->id;
		$moduleId = 'account';
		Yii::$app->view->title = Yii::t($moduleId . SL . $controllerId, 'title');
		\App::$domain->navigation->breadcrumbs->create([$moduleId . SL . 'main', 'title']);
		\App::$domain->navigation->breadcrumbs->create([$moduleId . SL . $controllerId, 'title']);

		return parent::beforeAction($action);
	}

}
