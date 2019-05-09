<?php

$version = API_VERSION_STRING;

return [
	"GET {$version}/auth" => "account/auth/info",
	"POST {$version}/auth" => "account/auth/login",
    "OPTIONS {$version}/auth" => "account/auth/options",

	"{$version}/registration/<action>" => "account/registration/<action>",

	"{$version}/restore-password/<action>" => "account/restore-password/<action>",

	"{$version}/security/<action>" => "account/security/<action>",

	["class" => "yii\\rest\UrlRule", "controller" => ["{$version}/user" => "account/user"]],
    ["class" => "yii\\rest\UrlRule", "controller" => ["{$version}/identity" => "account/identity"]],
];
