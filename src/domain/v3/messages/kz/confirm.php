<?php

use yii2rails\extension\yii\helpers\FileHelper;

$fileName = FileHelper::normalizePath(__FILE__);
$fileName = str_replace(DS . 'kz' . DS, DS . 'ru' . DS, $fileName);
return include($fileName);
