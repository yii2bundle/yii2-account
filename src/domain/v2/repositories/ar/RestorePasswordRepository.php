<?php

namespace yii2bundle\account\domain\v2\repositories\ar;

use Yii;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2bundle\account\domain\v2\entities\LoginEntity;
use yii2bundle\account\domain\v2\entities\SecurityEntity;
use yii2bundle\account\domain\v2\helpers\LoginHelper;
use yii2bundle\account\domain\v2\interfaces\repositories\RestorePasswordInterface;

class RestorePasswordRepository extends BaseRepository implements RestorePasswordInterface {

	const CONFIRM_ACTION = 'restore-password';
	
	public $smsCodeExpire = TimeEnum::SECOND_PER_HOUR;
	
	public function requestNewPassword($login, $mail = null) {
		$login = LoginHelper::getPhone($login);
		$entity = \App::$domain->account->confirm->createNew($login, self::CONFIRM_ACTION, $this->smsCodeExpire);
		$message = Yii::t('account/restore-password', 'restore_password_sms {activation_code}', ['activation_code' => $entity->activation_code]);
		\App::$domain->notify->sms->send($login, $message);
	}
	
	public function checkActivationCode($login, $code) {
		return \App::$domain->account->confirm->isVerifyCode($login, self::CONFIRM_ACTION, $code);
	}
	
	public function setNewPassword($login, $code, $password) {
		$login = LoginHelper::getPhone($login);
		/** @var LoginEntity $loginEntity */
		$loginEntity = \App::$domain->account->login->oneByLogin($login);
		/** @var SecurityEntity $securityEntity */
		$securityEntity = \App::$domain->account->security->oneById($loginEntity->id);
		$securityEntity->password_hash = Yii::$app->security->generatePasswordHash($password);
		\App::$domain->account->security->updateById($securityEntity->id, $securityEntity);
		return \App::$domain->account->confirm->delete($login, self::CONFIRM_ACTION);
	}
	
}