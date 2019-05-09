<?php

namespace yii2module\account\domain\v3\repositories\base;

use yii\base\InvalidArgumentException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2module\account\domain\v3\helpers\LoginTypeHelper;
use yii2module\account\domain\v3\strategies\login\LoginContext;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\entities\SecurityEntity;
use yii2module\account\domain\v3\helpers\TokenHelper;
use yii2module\account\domain\v3\interfaces\repositories\AuthInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class BaseAuthRepository
 *
 * @package yii2module\account\domain\v3\repositories\base
 * @property \yii2module\account\domain\v3\Domain $domain
 */
class __BaseAuthRepository extends BaseRepository implements AuthInterface {
	
	public function authentication($login, $password, $ip = null) {
		try {
            $query = new Query;
			$query->with('assignments');
			//$query->with('person');
            /*if(\App::$domain->has('staff')) {
	            $query->with('company');
            }*/
			/** @var LoginEntity $loginEntity */
			$loginContext = new LoginContext;
			$loginId = $loginContext->getLoginId($login);
			$loginEntity = \App::$domain->account->identity->oneById($loginId, $query);
			//$loginEntity = $this->domain->repositories->login->oneByVirtual($login, $query);
		} catch(NotFoundHttpException $e) {
			return false;
		}
		if(empty($loginEntity)) {
			return false;
		}
		$isValidPassword = $this->domain->security->isValidPassword($loginEntity->id, $password);
		if($isValidPassword) {
			$loginEntity->token = $this->domain->token->forge($loginEntity->id, $ip);
			return $loginEntity;
		}
		return false;
	}

    public function authenticationByToken($token, $type = null, $ip = null) {
	    if(!LoginTypeHelper::isToken($token)) {
		    throw new InvalidArgumentException('Invalid phone');
	    }
	    $loginContext = new LoginContext;
	    $loginId = $loginContext->getLoginId($token);
	    $query = new Query;
	    $query->with('assignments');
	    $loginEntity = \App::$domain->account->identity->oneById($loginId, $query);
	    return $loginEntity;
    }

}
