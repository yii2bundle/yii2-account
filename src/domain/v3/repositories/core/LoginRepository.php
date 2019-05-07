<?php

namespace yii2module\account\domain\v3\repositories\core;

use Yii;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii2rails\extension\core\domain\helpers\CoreHelper;
use yii2rails\extension\core\domain\repositories\base\BaseActiveCoreRepository;
use yii2rails\domain\data\Query;
use yii2rails\domain\traits\repository\CacheTrait;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2lab\rest\domain\helpers\RestHelper;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\interfaces\repositories\LoginInterface;

class LoginRepository extends BaseActiveCoreRepository implements LoginInterface {
	
	use CacheTrait;
	
	public $point = 'user';
	public $version = 1;
	
	public function isExistsByLogin($login) {
		try {
			$this->oneByLogin($login);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function oneById($id, Query $query = null) {
		$closure = function($data) {
			return $data instanceof IdentityInterface && $data->id > 0;
		};
		$userEntity = $this->cacheMethod(__FUNCTION__, func_get_args(), TimeEnum::SECOND_PER_HOUR, $closure);
		return $userEntity;
	}
	
	public function oneByLogin($login, Query $query = null) {
		return $this->oneById($login);
	}
	
	public function oneByToken(string $token, Query $query = null, $type = null) {
		$url = CoreHelper::forgeUrl($this->version, 'auth');
		$headers = CoreHelper::getHeaders();
		$headers[HttpHeaderEnum::AUTHORIZATION] = $token;
		$response = RestHelper::get($url, [], $headers);
		
		$data = $response->data;
		
		if(empty($data['id'])) {
			if($response->status_code == 401) {
				throw new NotFoundHttpException(__METHOD__ . ': ' . __LINE__);
			}
			/*if(empty($response->data['id'])) {
				throw new NotFoundHttpException(__METHOD__ . ': ' . __LINE__);
			}*/
			throw new NotFoundHttpException(__METHOD__ . ': ' . __LINE__);
		}
		
		return $this->forgeEntity($response);
	}
	
	public function forgeEntity($data, $class = null) {
		/** @var LoginEntity $entity */
		$entity = parent::forgeEntity($data, $class);
		if(empty($entity->status)) {
			$entity->status = \App::$domain->account->login->defaultStatus;
		}
		return $entity;
	}

    public function oneByPhone(string $phone, Query $query = null) : LoginEntity {
        $query = Query::forge($query);
        $personEntity = \App::$domain->user->person->oneByPhone($phone);
        $query->where(['person_id' => $personEntity->id]);
        $loginEntity = $this->one($query);
        return $loginEntity;
    }

    public function oneByEmail(string $email, Query $query = null) : LoginEntity {
        $query = Query::forge($query);
        try {
            $boxEntity = $this->oneBoxByEmail($email);
        } catch (NotFoundHttpException $e) {
            $error = new ErrorCollection;
            $error->add('login', 'mail/box', 'not_found');
            throw new UnprocessableEntityHttpException($error, 0, $e);
        }
        $query->where([
            'login' => $boxEntity->person->user->login,
            'company_id' => $boxEntity->domain->company_id,
        ]);
        $loginEntity = $this->one($query);
        return $loginEntity;
    }

    public function oneByVirtual(string $login, Query $query = null) : LoginEntity
    {
        $query = Query::forge($query);
        if(LoginTypeHelper::isPhone($login)) {
            $loginEntity = $this->oneByPhone($login, $query);
        } elseif(LoginTypeHelper::isEmail($login)) {
            $loginEntity = $this->oneByEmail($login, $query);
        } else {
            $loginEntity = $this->oneByLogin($login, $query);
        }
        return $loginEntity;
    }

}