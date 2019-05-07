<?php

namespace yii2module\account\domain\v3\repositories\ldap;

use App;
use tests\unit\helpers\RegistryTest;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\helpers\RestHelper;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\common\enums\StatusEnum;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\entities\SecurityEntity;
use yii2module\account\domain\v3\helpers\TokenHelper;
use yii2module\account\domain\v3\interfaces\repositories\AuthInterface;
use yii2module\account\domain\v3\validators\PasswordValidator;
use yubundle\reference\domain\entities\BookEntity;
use yubundle\reference\domain\entities\ItemEntity;
use yubundle\staff\domain\v1\entities\CompanyEntity;
use yubundle\staff\domain\v1\entities\DivisionEntity;
use yubundle\staff\domain\v1\entities\WorkerEntity;
use yubundle\user\domain\v1\entities\ClientEntity;
use yubundle\user\domain\v1\entities\PersonEntity;
use yii2module\account\domain\v3\forms\registration\PersonInfoForm;
use yii\helpers\ArrayHelper;

class AuthRepository extends BaseRepository implements AuthInterface {

    public function authentication($login, $password, $ip = null) {
        $post = [
            'login' => $login,
            'password' => $password,
        ];

        //TODO: вынести в файл настроек
        $url = 'http://dc.ttc.yumail.kz';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $ldapData = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        //TODO продумаьт обработку ошибок с сервера
        if ($httpcode == 422) {
            $query = new Query;
            $query->with(['person', 'company']);
            try {
                $loginEntity = $this->domain->repositories->login->oneByLogin($login, $query);
                $this->disablePerson($loginEntity->person_id);
            } catch (UnprocessableEntityHttpException $e) {

            }
            $error = new ErrorCollection();
            $error->add('person_id', 'Профиль работника был отключен');
            throw new UnprocessableEntityHttpException($error);
        } else if ($httpcode != 200 ) {
            $error = new ErrorCollection();
            $error->add('ldap', 'Ошибка на стороне сервера ldap');
            throw new UnprocessableEntityHttpException($error);
        }

        $ldapData = json_decode($ldapData, true);
        $checkLogin = $this->domain->repositories->login->isExistsByLogin($login);

        /** @var $loginEntity LoginEntity */
        if ($httpcode == 200 && $checkLogin) {
            $query = new Query;
            $query->with(['assignments', 'person', 'company']);
            $loginEntity = $this->domain->repositories->login->oneByVirtual($login, $query);
        } else if ($httpcode == 200 && !$checkLogin) {
            //TODO : Цепочка обязанностей
            $personInfoForm = new PersonInfoForm();

            $ldapData = $this->getProperties($ldapData);

            $personInfoForm->login = $login;
            $personInfoForm->last_name = ArrayHelper::getValue($ldapData, 'last_name');
            $personInfoForm->first_name =  ArrayHelper::getValue($ldapData, 'name');
            $personInfoForm->middle_name = ArrayHelper::getValue($ldapData, 'middle_name');
            $personInfoForm->phone = ArrayHelper::getValue($ldapData, 'telephone_number');
            $personInfoForm->birthday_day = ArrayHelper::getValue($ldapData, 'birthday_day');
            $personInfoForm->birthday_month = ArrayHelper::getValue($ldapData, 'birthday_month');
            $personInfoForm->birthday_year = ArrayHelper::getValue($ldapData, 'birthday_year');
            $personInfoForm->password = $password;
            $personInfoForm->password_confirm = $password;
            $personInfoForm->birth_date = ArrayHelper::getValue($ldapData, 'birth_date', date('Y-m-d'));

            $companyName = ArrayHelper::getValue($ldapData, 'company');
            try {
                $companyEntity = $this->checkExistsCompanyByName($companyName);
            } catch (NotFoundHttpException $e) {
                $companyCode = ArrayHelper::getValue($ldapData, 'company_code', rand(10, 20));
                $companyEntity = $this->createCompany($companyName, $companyCode);
            }

            $personInfoForm->company_id = $companyEntity->id;

            $loginEntity = App::$domain->account->login->createWeb($personInfoForm);
            $personEntity = App::$domain->user->person->oneById($loginEntity->person_id);

            try {
                $bookEntity = $this->checkExistsBookByCompanyId($companyEntity->id);
            } catch (NotFoundHttpException $e) {
                $bookEntity = $this->createBook($companyEntity);
            }

            $divisionName = ArrayHelper::getValue($ldapData, 'department', 'Остальные');
            try {
                $divisionEntity = $this->checkExistDivisionByName($divisionName, $companyEntity->id);
            } catch (NotFoundHttpException $e) {
                $divisionEntity = $this->createDivision($divisionName, $companyEntity->id);
            }

            $postName = ArrayHelper::getValue($ldapData, 'description', 'Название должности');
            try {
                $itemEntity = $this->checkExistsItemByValue($postName, $bookEntity->id);
            } catch (NotFoundHttpException $e) {
                $itemEntity = $this->createItem($postName, $bookEntity->id);
            }

            try {
                $this->checkExistWorkerByPersonId($personEntity->id);
            } catch (NotFoundHttpException $e) {
                $workerEntity = new WorkerEntity();
                $workerEntity->division_id = $divisionEntity->id;
                $workerEntity->phone = $personInfoForm->phone;
                $workerEntity->email = ArrayHelper::getValue($ldapData, 'mail', 'email@yuwert.kz');//$ldapData['mail'];
                $workerEntity->post_id = $itemEntity->id;
                $workerEntity->person_id = $loginEntity->person_id;
                $workerEntity->company_id = $companyEntity->id;
                $workerEntity = App::$domain->staff->worker->repository->insert($workerEntity);
            }

            $query = new Query;
            $query->with(['assignments', 'person', 'company']);
            $loginEntity = $this->domain->repositories->login->oneByVirtual($login, $query);
        }

        try {
            $securityEntity = $this->domain->repositories->security->validatePassword($loginEntity->id, $password);
        } catch(UnprocessableEntityHttpException $e) {
            $error = new ErrorCollection();
            $error->add('password', 'Неправильный логин или пароль');
            throw new UnprocessableEntityHttpException($error);
        }

        $securityEntity->token = $this->domain->token->forge($loginEntity->id, $ip);
        $loginEntity->security = $securityEntity;

        return $loginEntity;
    }

    public function authenticationByToken($token, $type = null, $ip = null) {
        try {
            $loginEntity = TokenHelper::authByToken($token, $this->domain->auth->tokenAuthMethods);
        } catch(NotFoundHttpException $e) {
            throw new UnauthorizedHttpException();
        }
        return $loginEntity;
    }

    private function createCompany($companyName, $companyCode) {
        $companyEntity = new CompanyEntity();
        $companyEntity->name = $companyName;
        $companyEntity->code = $companyCode;
        $companyEntity->status = StatusEnum::ENABLE;
        $companyEntity = App::$domain->staff->company->repository->insert($companyEntity);
        return $companyEntity;
    }

    private function checkExistsCompanyByName($companyName) {
        $query = new Query();
        $query->andWhere(['name' => $companyName]);
        $companyEntity = App::$domain->staff->company->repository->one($query);
        return $companyEntity;
    }

    private function checkExistsBookByCompanyId($companyId) {
        $query = new Query();
        $query->andWhere(['owner_id' => $companyId]);
        $bookEntity = App::$domain->reference->book->repository->one($query);
        return $bookEntity;
    }

    private function createBook($companyEntity) {
        $bookEntity = new BookEntity();
        $bookEntity->name = $companyEntity->name;
        $bookEntity->entity = 'posts';
        $bookEntity->owner_id = $companyEntity->id;
        $bookEntity = App::$domain->reference->book->repository->insert($bookEntity);
        return $bookEntity;
    }

    private function checkExistsItemByValue($value, $bookId) {
        $query = new Query();
        $query->andWhere(['value' => $value]);
        $entityItem = App::$domain->reference->item->one($query);
        return $entityItem;
    }

    private function createItem($value, $bookId) {
        $itemEntity = new ItemEntity();
        $itemEntity->value = $value;
        $itemEntity->short_value = $value;
        $itemEntity->entity = $value;
        $itemEntity->reference_book_id = $bookId;
        $itemEntity = App::$domain->reference->item->repository->insert($itemEntity);
        return $itemEntity;
    }

    private function checkExistDivisionByName($name, $companyId) {
        $query = new Query();
        $query->andWhere(['name' => $name]);
        $query->andWhere(['company_id' => $companyId]);
        $divisionEntity = App::$domain->staff->division->repository->one($query);
        return $divisionEntity;
    }

    private function createDivision($name, $companyId) {
        $divisionEntity = new DivisionEntity();
        $divisionEntity->name = $name;
        $divisionEntity->company_id = $companyId;
        $divisionEntity = App::$domain->staff->division->repository->insert($divisionEntity);
        return $divisionEntity;
    }

    private function checkExistWorkerByPersonId($personId) {
        $query = new Query();
        $query->andWhere(['person_id' => $personId]);
        $workerEntity = App::$domain->staff->worker->repository->one($query);
        return $workerEntity;
    }

    private function getProperties($properties) {
        $filteredProperties = array_filter($properties);

        $defaultProperties = [
            "full_name" => "Имя Фамилия",
            "last_name" => "Фамилия",
            "name" => "Имя",
            "country_code" => "Код страны",
            "country_name" => "Название страны",
            "region" => "Название региона",
            "street_address" => "Адресс",
            "title" => null,
            "description" => "Должность",
            "postal_code" => "asdfasdfazds",
            "post_officebox" => "asdfadsf",
            "telephone_number" => "+77777777777",
            "ip_phone" => "127.0.0.1",
            "home_phone" => "+77777777777",
            "mobile" => "+77777777777",
            "when_created" => null,
            "when_changed" => null,
            "company" => "Название компании",
            "department" => "Остальные",
            "user_principalname" => "user@yumail.local",
            "mail" => "user@yuwert.kz",
            "birthday_day" => date('d'),
            "birthday_month" => date('m'),
            "birthday_year" =>  date('Y'),
        ];

        return ArrayHelper::merge($defaultProperties, $filteredProperties);
    }

    private function disablePerson($id) {
        $entity = App::$domain->user->person->repository->oneById($id);
        $data = [
            'status' => StatusEnum::DISABLE,
        ];
        $entity->load($data);
        App::$domain->user->person->repository->update($entity);
    }

}
