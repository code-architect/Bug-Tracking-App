<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$app = new \App\Helpers\App();

set_exception_handler([new \App\Exception\ExceptionHandler(), 'handle']);

$config = \App\Helpers\Config::getFileContent('jbjbvj');

var_dump($config);

echo $app->getEnvironment().PHP_EOL;
echo $app->getLogPath().PHP_EOL;
echo $app->isDebugMode().PHP_EOL;
echo $app->isRunningFromConsole().PHP_EOL;
echo $app->getServerTime()->format('Y-m-d H:i:s').PHP_EOL;
