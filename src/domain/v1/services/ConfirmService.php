<?php

namespace yii2module\account\domain\v1\services;

use yii\db\IntegrityException;
use yii\web\NotFoundHttpException;
use yii2lab\domain\data\Query;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\ActiveBaseService;
use yii2module\account\domain\v1\helpers\ConfirmHelper;
use yii2module\account\domain\v1\helpers\LoginHelper;

class ConfirmService extends ActiveBaseService {
	
	public function delete($login, $action) {
		$login = LoginHelper::getPhone($login);
		$this->beforeAction(self::EVENT_DELETE);
		$query = Query::forge();
		$query->where('login', $login);
		$query->where('action', $action);
		$entity = $this->one($query);
		$this->repository->delete($entity);
		return $this->afterAction(self::EVENT_DELETE);
	}
	
	public function isVerifyCode($login, $action, $code, $smsCodeExpire) {
		$login = LoginHelper::getPhone($login);
		try {
			$confirmEntity = $this->oneByLoginAndAction($login, $action);
		} catch(NotFoundHttpException $e) {
			return false;
		}
		if ($confirmEntity->code != $code) {
			return false;
		}
		return true;
	}
	
	public function oneByLoginAndAction($login, $action, $smsCodeExpire) {
		$login = LoginHelper::getPhone($login);
		$this->cleanOld($login, $action, $smsCodeExpire);
		return $this->repository->oneByLoginAndAction($login, $action);
	}
	
	private function cleanOld($login, $action, $smsCodeExpire) {
		$login = LoginHelper::getPhone($login);
		if(!empty($smsCodeExpire)) {
			$this->repository->cleanOld($login, $action, $smsCodeExpire);
		}
	}
	
	public function createNew($login, $action, $smsCodeExpire) {
		$login = LoginHelper::getPhone($login);
		$this->cleanOld($login, $action, $smsCodeExpire);
		$data = compact(['login', 'action']);
		$data['code'] = ConfirmHelper::generateCode();
		try {
			return parent::create($data);
		} catch(IntegrityException $e) {
			$error = new ErrorCollection();
			$error->add('login', 'account/auth', 'already_sended_code');
			throw new UnprocessableEntityHttpException($error);
		}
	}
}
