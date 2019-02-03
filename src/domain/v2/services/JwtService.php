<?php

namespace yii2module\account\domain\v2\services;

use yii\base\InvalidConfigException;
use yii\base\Object;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\jwt\entities\JwtEntity;
use yii2module\account\domain\v2\interfaces\services\JwtInterface;
use yii2rails\domain\services\base\BaseService;
use yii2rails\domain\Alias;
use yii2rails\extension\yii\helpers\ArrayHelper;

/**
 * Class JwtService
 * 
 * @package yii2module\account\domain\v2\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\JwtInterface $repository
 */
class JwtService extends BaseService implements JwtInterface {

    const DEFAULT_PROFILE = 'default';

    public function forge($subject, $profileName = self::DEFAULT_PROFILE) {
        $jwtEntity = new JwtEntity();
        $jwtEntity->subject = $subject;
        $this->prepEntity($jwtEntity);
        \App::$domain->jwt->jwt->sign($jwtEntity, $profileName);
        return $jwtEntity;
    }

    public function decode($token, $profileName = self::DEFAULT_PROFILE) {
        $jwtEntity = \App::$domain->jwt->jwt->decode($token, $profileName);
        return $jwtEntity;
    }

    private function prepEntity(JwtEntity $jwtEntity) {
        if(!$jwtEntity->issuer_url) {
            $jwtEntity->issuer_url = EnvService::getUrl(API, 'v1/auth');
        }
        $userId = ArrayHelper::getValue($jwtEntity, 'subject.id');
        if($userId) {
            if(!$jwtEntity->subject_url) {
                $jwtEntity->subject_url = EnvService::getUrl(API, 'v1/user/' . $userId);
            }
        }
    }

}

/**
 * Example
 *
 *
 * 'account' => [
'jwt' => [
'profiles' => [
'default' => [
'key' => 'qwerty123',
'life_time' => \yii2rails\extension\enum\enums\TimeEnum::SECOND_PER_MINUTE * 20,
'allowed_algs' => ['HS256', 'SHA512', 'HS384'],
'default_alg' => 'HS256',
'audience' => ["http://api.core.yii"],
],
],
],
],
 *
 *
 * $jwtEntity = new JwtEntity();
//$jwtEntity->audience = ["http://api.example.yii"];
$jwtEntity->subject_id = \App::$domain->account->auth->identity->id;
//$jwtEntity->subject_url = "http://api.core.yii/v1/user/" . $jwtEntity->subject_id;

\App::$domain->account->jwt->sign($jwtEntity);
$jwt = $jwtEntity->token;
$decodedEntity = \App::$domain->account->jwt->decode($jwt);

if($decodedEntity->toArray() != $jwtEntity->toArray()) {
prr('Not equaled!',1,1);
}
prr($jwtEntity,1,1);
 *
 */