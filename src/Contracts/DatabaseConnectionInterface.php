<?php
declare(strict_types = 1);
namespace App\Contracts;

interface DatabaseConnectionInterface
{
    public function connect();
    public function getConnection();
}