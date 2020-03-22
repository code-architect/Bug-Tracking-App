<?php
declare(strict_types = 1);

namespace App\Database;
use PDO;

class PDOQueryBuilder extends QueryBuilder
{

    public function get()
    {
        return $this->statement->fetchAll();
    }

    public function count()
    {
        return $this->statement->rowCount();
    }

    public function lastInsertedId()
    {
        return $this->conn->lastInsertId();
    }

    public function prepare($query)
    {
        return $this->conn->prepare($query);
    }

    public function execute($statement)
    {
        $statement->execute($this->bindings);
        $this->bindings = [];
        $this->placeholders = [];
        return $statement;
    }

    public function fetchInto($className)
    {
        return $this->statement->fetchAll(PDO::FETCH_CLASS, $className);
    }

    public function beginTransaction()
    {
        $this->conn->beginTransaction();
    }

    public function affected()
    {
        return $this->count();
    }
}