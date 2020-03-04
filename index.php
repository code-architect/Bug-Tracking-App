<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$config = \App\Helpers\Config::get('app', 'app_name');

var_dump($config);