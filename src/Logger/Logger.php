<?php
declare(strict_types = 1);
namespace App\Logger;

use App\Contracts\LoggerInterface;
use App\Exception\InvalidLogLevelArgument;
use App\Helpers\App;

class Logger implements LoggerInterface
{

    public function emergency(string $message, array $context = [])
    {
        $this->addRecord(Loglevel::EMERGENCY, $message, $context);
    }

    public function alert(string $message, array $context = [])
    {
        $this->addRecord(Loglevel::ALERT, $message, $context);
    }

    public function critical(string $message, array $context = [])
    {
        $this->addRecord(Loglevel::CRITICAL, $message, $context);
    }

    public function error(string $message, array $context = [])
    {
        $this->addRecord(Loglevel::ERROR, $message, $context);
    }

    public function warning(string $message, array $context = [])
    {
        $this->addRecord(Loglevel::WARNING, $message, $context);
    }

    public function notice(string $message, array $context = [])
    {
        $this->addRecord(Loglevel::NOTICE, $message, $context);
    }

    public function info(string $message, array $context = [])
    {
        $this->addRecord(Loglevel::INFO, $message, $context);
    }

    public function debug(string $message, array $context = [])
    {
        $this->addRecord(Loglevel::DEBUG, $message, $context);
    }


    public function log(string $level, string $message, array $context = [])
    {
        $object = new \ReflectionClass(Loglevel::class);
        $validLevels = $object->getConstants();
        if(!in_array($level, $validLevels))
        {
            throw new InvalidLogLevelArgument($level, $validLevels);
        }
        $this->addRecord($level, $message, $context);
    }


    private function addRecord(string $level, string $message, array $context = [])
    {
        $app = new App;
        $date = $app->getServerTime()->format('Y-m-d H:i:s');
        $logPath= $app->getLogPath();
        $env = $app->getEnvironment();
        $details = sprintf(
            "%s - Level: %s - Env: %s - Message: %s - Context: %s", $date, $level, $env, $message, json_encode($context)
        ).PHP_EOL;

        $fileName = sprintf("%s/%s-%s.log", $logPath, $env, date("j.n.Y"));
        file_put_contents($fileName, $details, FILE_APPEND);
    }
}