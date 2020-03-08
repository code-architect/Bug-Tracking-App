<?php

namespace Test\Unit;

use App\Database\PDOConnection;
use App\Exception\MissingArgumentException;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testItThrowsMissingArgumentWithWrongKeys()
    {
        self::expectException(MissingArgumentException::class);
        $credentials = [];
        $pdo = new PDOConnection($credentials);
    }

    public function testItCanConnectToDatabaseWithPdoApi()
    {
        $credentials = [];
        $pdo = (new PDOConnection($credentials))->connect();
        self::assertNotNull($pdo);
    }
}