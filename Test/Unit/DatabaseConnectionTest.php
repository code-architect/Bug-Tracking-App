<?php

namespace Test\Unit;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\MySQLiConnection;
use App\Database\PDOConnection;
use App\Exception\MissingArgumentException;
use App\Helpers\Config;
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
        $credentials = $this->getCredentials('pdo');
        $pdo = (new PDOConnection($credentials))->connect();
        self::assertInstanceOf(DatabaseConnectionInterface::class, $pdo, 'It is an instance of');
        return $pdo;
    }


    /**
     * @param DatabaseConnectionInterface $handler Interface
     * @depends testItCanConnectToDatabaseWithPdoApi
     */
    public function testItIsAValidPdoConnection(DatabaseConnectionInterface $handler)
    {
        self::assertInstanceOf(\PDO::class, $handler->getConnection());
    }


    public function testItCanConnectToDatabaseWithMysqliApi()
    {
        $credentials = $this->getCredentials('mysqli');
        $mysqli = (new MySQLiConnection($credentials))->connect();
        self::assertInstanceOf(DatabaseConnectionInterface::class, $mysqli, 'It is an instance of');
        return $mysqli;
    }


    /**
     * @param DatabaseConnectionInterface $handler Interface
     * @depends testItCanConnectToDatabaseWithMysqliApi
     */
    public function testItIsAValidMysqliConnection(DatabaseConnectionInterface $handler)
    {
        self::assertInstanceOf(\mysqli::class, $handler->getConnection());
    }


    private function getCredentials(string $type)
    {
        return array_merge(
            Config::get('database', $type),
            ['db_name' =>  'bug_app_testing']
        );
    }
}