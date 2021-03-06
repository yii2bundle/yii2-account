<?php

$version = API_VERSION_STRING;

return [
	"GET {$version}/auth" => "account/auth/info",
	"POST {$version}/auth" => "account/auth/login",

	"{$version}/registration/<action:(create-account|activate-account|set-password)>" => "account/registration/<action>",

	"{$version}/auth/restore-password/<action:(request|check-code|confirm)>" => "account/restore-password/<action>",

	"{$version}/security/<action:(password|email)>" => "account/security/<action>",

    ["class" => "yii\\rest\UrlRule", "controller" => ["{$version}/user" => "account/user"]],
];
