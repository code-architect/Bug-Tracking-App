<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

require_once __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'exception.php';

$test = new \App\Database\PDOConnection( [
    'driver'            =>  'mysql',
    'host'              =>  'localhost',
    'db_name'           =>  'bug_app',
    'db_username'       =>  'root',
    'db_user_password'  =>  '',
    'default_fetch'     =>  PDO::FETCH_OBJ
]);
var_dump($test->connect());