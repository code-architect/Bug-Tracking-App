<?php
declare(strict_types = 1);
namespace App\Contracts;

interface LoggerInterface
{
    //based on psr-3
    public function emergency(string $message, array $context = []);    // when system is unusable, system is down
    public function alert(string $message, array $context = []);    // when database is down
    public function critical(string $message, array $context = []); // system critical condition
    public function error(string $message, array $context = []);  // runtime error
    public function warning(string $message, array $context = []); // exception
    public function notice(string $message, array $context = []);  // don't stop the app but significant events
    public function info(string $message, array $context = []);  // user record was created etc.
    public function debug(string $message, array $context = []); // log detail info to debug the app
    public function log(string  $level, string $message, array $context = []);  // optional and pass the level of our choice
}