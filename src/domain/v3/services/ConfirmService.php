<?php

namespace yii2bundle\account\domain\v3\services;

use Yii;
use yii\web\NotFoundHttpException;
use yii2rails\domain\services\base\BaseActiveService;
use yii2lab\notify\domain\exceptions\SmsTimeLimitException;
use yii2bundle\account\domain\v3\entities\ConfirmEntity;
use yii2bundle\account\domain\v3\exceptions\ConfirmAlreadyExistsException;
use yii2bundle\account\domain\v3\exceptions\ConfirmIncorrectCodeException;
use yii2bundle\account\domain\v3\helpers\ConfirmHelper;
use yii2bundle\account\domain\v3\helpers\LoginHelper;
use yii2bundle\account\domain\v3\interfaces\services\ConfirmInterface;

/**
 * Class ConfirmService
 *
 * @package yii2bundle\account\domain\v3\services
 * @property \yii2bundle\account\domain\v3\interfaces\repositories\ConfirmInterface $repository
 */
class ConfirmService extends BaseActiveService implements ConfirmInterface
{
	
	public function delete($login, $action)
	{
		$login = LoginHelper::getPhone($login);
		$this->beforeAction(self::EVENT_DELETE);
		$this->repository->cleanAll($login, $action);
		return $this->afterAction(self::EVENT_DELETE);
	}
	
	public function isActivated($login, $action)
	{
		$login = LoginHelper::getPhone($login);
		$confirmEntity = $this->oneByLoginAndAction($login, $action);
		return $confirmEntity->is_activated;
	}
	
	public function activate($login, $action, $code)
	{
		$login = LoginHelper::getPhone($login);
		$confirmEntity = $this->oneByLoginAndAction($login, $action);
		$confirmEntity->activate($code);
		$this->repository->update($confirmEntity);
		//return $this->isActivated($login, $action);
	}
	
	public function verifyCode($login, $action, $code)
	{
		$login = LoginHelper::getPhone($login);
		$confirmEntity = $this->oneByLoginAndAction($login, $action);
		if($confirmEntity->code != $code) {
			throw new ConfirmIncorrectCodeException(Yii::t('account/confirm', 'incorrect_code'));
		}
		return $confirmEntity;
	}
	
	public function isVerifyCode($login, $action, $code)
	{
		$login = LoginHelper::getPhone($login);
		$confirmEntity = $this->oneByLoginAndAction($login, $action);
		if($confirmEntity->code != $code) {
			return false;
		}
		return true;
	}
	
	public function isHas($login, $action)
	{
		try {
			$this->oneByLoginAndAction($login, $action);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	/**
	 * @param $login
	 * @param $action
	 *
	 * @return ConfirmEntity
	 * @throws NotFoundHttpException
	 */
	private function oneByLoginAndAction($login, $action)
	{
		$login = LoginHelper::getPhone($login);
		$this->cleanOld($login, $action);
		try {
            return $this->repository->oneByLoginAndAction($login, $action);
        } catch (NotFoundHttpException $e) {
		    throw new NotFoundHttpException(Yii::t('account/confirm', 'not_found'), 0, $e);
        }
	}
	
	public function send($login, $action, $expire, $data = null)
	{
		/** @var ConfirmEntity $confirmEntity */
		$confirmEntity = $this->createNew($login, $action, $expire, $data);
		$this->sendSms($login, $confirmEntity->code);
	}
	
	protected function createNew($login, $action, $expire, $data = null)
	{
		$login = LoginHelper::getPhone($login);
		$this->cleanOld($login, $action);
		$entityArray['login'] = $login;
		$entityArray['action'] = $action;
		$entityArray['data'] = $data;
		$entityArray['expire'] = TIMESTAMP + $expire;
		$entityArray['code'] = ConfirmHelper::generateCode();
		if($this->isHas($login, $action)) {
			throw new ConfirmAlreadyExistsException();
		}
		return parent::create($entityArray);
	}
	
	private function cleanOld($login, $action)
	{
		$login = LoginHelper::getPhone($login);
		$this->repository->cleanOld($login, $action);
	}
	
	protected function sendSms($login, $activation_code)
	{
		$login = LoginHelper::pregMatchLogin($login);
		$loginParts = LoginHelper::splitLogin($login);
		$message = Yii::t('account/confirm', 'confirmation_code {code}', ['code' => $activation_code]);
		try {
			\App::$domain->notify->sms->send($loginParts['country_code'] . $loginParts['phone'], $message);
		} catch(SmsTimeLimitException $e) {
			throw new ConfirmAlreadyExistsException;
		}
	}
}
