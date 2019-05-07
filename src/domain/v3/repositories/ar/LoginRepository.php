<?php

namespace yii2module\account\domain\v3\repositories\ar;

use domain\mail\v1\entities\BoxEntity;
use domain\mail\v1\entities\DomainEntity;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2module\account\domain\v3\repositories\traits\LoginTrait;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\helpers\LoginTypeHelper;
use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2module\account\domain\v3\interfaces\repositories\LoginInterface;

/**
 * Class LoginRepository
 *
 * @package yii2module\account\domain\v3\repositories\ar
 *
 * @property-read \yubundle\user\domain\v1\Domain $domain
 */
class LoginRepository extends BaseActiveArRepository implements LoginInterface {

	protected $schemaClass = true;

	public function tableName()
    {
        return 'user_login';
    }

    public function uniqueFields() {
        return ['login'];
    }

    public function insert(BaseEntity $loginEntity) {
		$loginEntity->validate();
		/** @var LoginEntity $loginEntity */
		$this->findUnique($loginEntity);
		$data = [
			'person_id' => $loginEntity->person_id,
			'login' => $loginEntity->login,
            'company_id' => $loginEntity->company_id,
			'password' => Yii::$app->security->generatePasswordHash($loginEntity->password),
		];
        $tableName = TableHelper::getGlobalName($this->tableName());
		Yii::$app->db->createCommand()->insert($tableName, $data)->execute();
		$loginEntity->id = Yii::$app->db->getLastInsertID();
	}
	
	public function isExistsByLogin($login) {
	    try {
		    $this->oneByLogin($login);
		    return true;
	    } catch(NotFoundHttpException $e) {
		    return false;
	    }
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

	public function oneByLogin($login, Query $query = null) : LoginEntity
    {
        $query = Query::forge($query);
        $query->where(['login' => $login]);
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

    public function oneByToken($token, Query $query = null, $type = null) {
        $query = Query::forge($query);
        /** @var TokenEntity $tokenEntity */
        $tokenEntity = $this->domain->token->validate($token);
        return $this->oneById($tokenEntity->user_id, $query);
    }

    protected function oneBoxByEmail(string $email, Query $query = null) : BoxEntity {
        $boxQuery = new Query;
        $boxQuery->andWhere(['email' => $email]);
        $boxQuery->with('domain');
        $boxQuery->with('person.user');

        /** @var BoxEntity $boxEntity */
        try {
            $boxEntity = \App::$domain->mail->box->oneByEmail($email, $boxQuery);
        } catch (NotFoundHttpException $e) {
            $emailEntity = \App::$domain->mail->address->oneByEmail($email);
            $domainEntity = \App::$domain->mail->companyDomain->oneByDomainName($emailEntity->domain);
            $identityEntity = \App::$domain->account->login->oneByLogin($emailEntity->login);

            $boxEntity = new BoxEntity;
            $boxEntity->domain_id = $domainEntity->id;
            $boxEntity->person_id = $identityEntity->person_id;
            $boxEntity->email = $email;

            /** @var BoxEntity $boxEntity */
            \App::$domain->mail->box->createEntity($boxEntity);
            $boxEntity = \App::$domain->mail->box->oneByEmail($email, $boxQuery);
        }
        return $boxEntity;
    }

}
