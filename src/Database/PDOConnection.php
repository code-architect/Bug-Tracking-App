<?php
namespace App\Database;
use App\Exception\DatabaseConnectionException;
use PDOException, PDO;
use App\Contracts\DatabaseConnectionInterface;

class PDOConnection extends AbstractConnection implements DatabaseConnectionInterface
{
    const REQUIRED_CONNECTION_KEYS = [
        'driver',
        'host',
        'db_name',
        'db_username',
        'db_user_password',
        'default_fetch'
    ];

    public function connect(): PDOConnection
    {
        $credentials = $this->parseCredentials($this->credentials);
        try{
            $this->conn = new PDO(...$credentials);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $this->credentials['default_fetch'] );
        }catch (PDOException $e)
        {
            throw new DatabaseConnectionException($e->getMessage(),$this->credentials, 500);
        }
        return $this;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }

    protected function parseCredentials(array $credentials): array
    {
        $dsn = sprintf(
            '%s:host=%s;dbname=%s',
            $credentials['driver'], $credentials['host'], $credentials['db_name']
        );
        return [$dsn, $credentials['db_username'], $credentials['db_user_password']];
    }
}