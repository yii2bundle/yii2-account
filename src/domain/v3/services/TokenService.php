<?php

namespace yii2module\account\domain\v3\services;

use Yii;
use yii\web\NotFoundHttpException;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2module\account\domain\v3\entities\TokenEntity;
use yii2module\account\domain\v3\exceptions\InvalidIpAddressException;
use yii2module\account\domain\v3\exceptions\NotFoundLoginException;
use yii2module\account\domain\v3\interfaces\services\TokenInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class TokenService
 * 
 * @package yii2module\account\domain\v3\services
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 * @property-read \yii2module\account\domain\v3\interfaces\repositories\TokenInterface $repository
 */
class TokenService extends BaseActiveService implements TokenInterface {
	
	public $defaultExpire = TimeEnum::SECOND_PER_HOUR;
	public $tokenLength = 128;
	public $autoRefresh = true;
	
	public function forge($userId, $ip, $expire = null) {
		try {
			$this->domain->login->oneById($userId);
		} catch(NotFoundHttpException $e) {
			throw new NotFoundLoginException($e->getMessage(), 0, $e);
		}
		if(empty($expire)) {
			$expire = $this->defaultExpire;
		}
		try {
			$tokenEntity = $this->oneByUserIdAndIp($userId, $ip);
			if($this->autoRefresh) {
				$tokenEntity->expire_at = TIMESTAMP + $expire;
				$this->repository->update($tokenEntity);
			}
			return $tokenEntity->token;
		} catch(NotFoundHttpException $e) {
			return $this->generate($userId, $ip, $expire);
		}
	}
	
	public function validate($token, $ip) {
		$tokenEntity = $this->oneByToken($token);
		$isValidIp = $tokenEntity->ip === $ip;
		if(!$isValidIp) {
			throw new InvalidIpAddressException();
		}
		return $tokenEntity;
	}
	
	public function deleteAllExpired() {
		$this->repository->deleteAllExpired();
	}
	
	public function deleteAll() {
		$this->repository->truncate();
	}
	
	public function deleteOneByToken($token) {
		$this->repository->deleteOneByToken($token);
	}
	
	private function oneByToken($token) {
		$tokenEntity = $this->repository->oneByToken($token);
		$isValid = $this->validateExpire($tokenEntity);
		if(!$isValid) {
			throw new NotFoundHttpException(__METHOD__ . ': ' . __LINE__);
		}
		return $tokenEntity;
	}
	
	private function existsByToken($token) {
		try {
			$this->oneByToken($token);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	private function allByIp($ip) {
		$collection = $this->repository->allByIp($ip);
		$collection = $this->filterCollectionByExpire($collection);
		return $collection;
	}
	
	private function allByUserId($userId) {
		$collection = $this->repository->allByUserId($userId);
		$collection = $this->filterCollectionByExpire($collection);
		return $collection;
	}
	
	private function generateUniqueToken() {
		$isExists = true;
		$token = null;
		while($isExists) {
			$token = Yii::$app->security->generateRandomString($this->tokenLength);
			$isExists = $this->existsByToken($token);
		}
		return $token;
	}
	
	private function generate($userId, $ip, $expire) {
		$token = $this->generateUniqueToken();
		$agentInfo = ClientHelper::getAgentInfo();
		$agentInfo['user_id'] = $userId;
		$agentInfo['ip'] = $ip;
		$agentInfo['token'] = $token;
		$agentInfo['expire_at'] = TIMESTAMP + $expire;
		if(!empty($agentInfo['version'])) {
            $agentInfo['version'] = $this->trimVersion($agentInfo['version']);
        }
		$tokenEntity = new TokenEntity();
		$tokenEntity->load($agentInfo);
		$this->repository->insert($tokenEntity);
		return $token;
	}

	private function trimVersion($version) {
        while(mb_strlen($version) > 10) {
            $version = FileHelper::fileRemoveExt($version);
        }
        return $version;
    }

	private function oneByIp($ip) {
		$collection = $this->allByIp($ip);
		if(empty($collection)) {
			throw new NotFoundHttpException(__METHOD__ . ': ' . __LINE__);
		}
		return $collection[0];
	}
	
	private function oneByUserIdAndIp($userId, $ip) {
		$collection = $this->allByUserId($userId);
		//prr($collection,1,1);
		/** @var TokenEntity $tokenEntity */
		$tokenEntity = $this->findByIp($collection, $ip);
		if(empty($tokenEntity)) {
			throw new NotFoundHttpException(__METHOD__ . ': ' . __LINE__);
		}
		return $tokenEntity;
	}
	
	private function filterCollectionByExpire($collection) {
		foreach($collection as $index => $tokenEntity) {
			if(!$this->isValidateExpire($tokenEntity)) {
				$this->repository->delete($tokenEntity);
				unset($collection[$index]);
			}
		}
		$collection = array_values($collection);
		return $collection;
	}
	
	private function findByIp($collection, $ip) {
		foreach($collection as $index => $tokenEntity) {
			if($tokenEntity->ip == $ip) {
				return $tokenEntity;
			}
		}
		return null;
	}
	
	private function validateExpire(TokenEntity $tokenEntity) {
		if($this->isValidateExpire($tokenEntity)) {
			return true;
		}
		$this->repository->delete($tokenEntity);
		return false;
	}
	
	private function isValidateExpire(TokenEntity $tokenEntity) {
		$isValidExpire = TIMESTAMP < $tokenEntity->expire_at;
		if($isValidExpire) {
			return true;
		}
		return false;
	}
	
}
