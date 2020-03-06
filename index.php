<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

require_once __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'exception.php';

$logger = new \App\Logger\Logger();

$logger->log(\App\Logger\Loglevel::ALERT, 'hello world', ['exception' => 'record occurred']);