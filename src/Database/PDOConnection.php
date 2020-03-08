<?php


namespace App\Database;


use App\Contracts\DatabaseConnectionInterface;

class PDOConnection extends AbstractConnection implements DatabaseConnectionInterface
{
    const REQUIRED_CONNECTION_KEYS = [
        'driver',
        'host',
        'db_name',
        'db_username',
        'db-user_password',
        'default_fetch'
    ];

    public function connect(): PDOConnection
    {
        return $this;
    }

    public function getConnection()
    {
        // TODO: Implement getConnection() method.
    }
}