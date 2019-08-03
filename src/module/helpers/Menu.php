<?php

namespace yii2bundle\account\module\helpers;

use Yii;
use yii2rails\extension\menu\interfaces\MenuInterface;
use yii2rails\extension\yii\helpers\Html;
use yii2bundle\account\domain\v3\helpers\LoginHelper;
use yii2module\profile\domain\v2\entities\PersonEntity;
use yii2module\profile\widget\Avatar;

class Menu implements MenuInterface {
	
	public function toArray() {
		return self::menu(null);
	}
	
	public static function menu($items) {
		return $menu = [
			'label' => self::getLabel(),
			'module' => 'user',
			'encode' => false,
			'items' => self::getItems($items),
		];
	}
	
	public static function getItems($items = null) {
		if(!empty($items)) {
			return $items;
		}
		if(Yii::$app->user->isGuest) {
			return self::getGuestMenu();
		} else {
			return self::getUserMenu();
		}
	}
	
	private static function getLabel() {
		if(Yii::$app->user->isGuest) {
			return Html::fa('user') . NBSP . Yii::t('account/auth', 'title');
		} else {
			return !class_exists(Avatar::class) ? self::getUseName() : Avatar::widget() . NBSP . self::getUseName();
		}
	}
	
	public static function getUseName() {
		$title = null;
		if(\App::$domain->has('profile')) {
			/** @var PersonEntity $personEntity */
			$personEntity = \App::$domain->profile->person->getSelf();
			$title = $personEntity->title;
		}
		if(!$title) {
			$title = \App::$domain->account->auth->identity->login;
			if(LoginHelper::validate($title)) {
				$title = LoginHelper::format($title);
			}
		}
		return $title;
	}
	
	private static function getGuestMenu()
	{
		return [
			[
				'label' => ['account/auth', 'login_action'],
				'url' => Yii::$app->user->loginUrl,
			],
			[
				'label' => ['account/registration', 'title'],
				'url' => 'user/registration',
			],
			[
				'label' => ['account/restore-password', 'title'],
				'url' => 'user/restore-password',
			],
		];
	}
	
	private static function getUserMenu()
	{
		return [
			'yii2module\profile\module\v1\helpers\Menu',
			//MenuHelper::DIVIDER,
			[
				'label' => ['account/security', 'title'],
				'url' => 'user/security',
			],
			[
				'label' => ['account/auth', 'logout_action'],
				'url' => 'user/auth/logout',
				'linkOptions' => ['data-method' => 'post'],
			],
		];
	}

}
