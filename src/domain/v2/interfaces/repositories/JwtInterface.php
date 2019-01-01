<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii2module\account\domain\v2\entities\JwtEntity;
use yii2module\account\domain\v2\entities\JwtProfileEntity;

/**
 * Interface JwtInterface
 * 
 * @package yii2module\account\domain\v2\interfaces\repositories
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
interface JwtInterface {

    public function sign(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity);
    public function encode(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity);
    public function decode($token, JwtProfileEntity $profileEntity);

}
