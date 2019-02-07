<?php

namespace yii2module\account\domain\v3\interfaces\repositories;

use yii2module\account\domain\v3\entities\JwtEntity;
use yii2module\account\domain\v3\entities\JwtProfileEntity;

/**
 * Interface JwtInterface
 * 
 * @package yii2module\account\domain\v3\interfaces\repositories
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 */
interface JwtInterface {

    public function sign(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity);
    public function encode(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity);
    public function decode($token, JwtProfileEntity $profileEntity);

}
