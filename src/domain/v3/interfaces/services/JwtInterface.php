<?php

namespace yii2module\account\domain\v3\interfaces\services;

use yii2rails\extension\jwt\entities\JwtEntity;


/**
 * Interface JwtInterface
 * 
 * @package yii2module\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 * @property-read \yii2module\account\domain\v3\interfaces\repositories\JwtInterface $repository
 */
interface JwtInterface {

    public function forge($subject, $profileName = self::DEFAULT_PROFILE);
    //public function encode(JwtEntity $jwtEntity);
    public function decode($token);

}