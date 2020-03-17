<?php


namespace App\Database;

use App\Exception\InvalidArgumentException;

class MySQLiQueryBuilder extends QueryBuilder
{

    private $resultSet;
    private $results;

    const PARAM_TYPE_INT    = 'i';
    const PARAM_TYPE_STRING = 's';
    const PARAM_TYPE_DOUBLE = 'd';

    public function get()
    {
        $results = [];
        if(!$this->resultSet){
            $this->resultSet = $this->statement->get_result();
            if($this->resultSet){
                while($object = $this->resultSet->fetch_object()){
                    $results[] = $object;
                }
                $this->results = $results;
            }
        }
        return $this->results;
    }

    public function count()
    {
        if(!$this->resultSet) {
            $this->get();
        }
        return $this->resultSet ? $this->resultSet->num_rows : false;
    }

    public function lastInsertedId()
    {
        return $this->conn->insert_id;
    }

    public function prepare($query)
    {
        return $this->conn->prepare($query);
    }

    public function execute($statement)
    {
        if(!$statement) {
            throw new InvalidArgumentException('MySQLi statement is false');
        }

        if($this->bindings){
            $bindings = $this->parseBinding($this->bindings);
            $reflectionObj = new \ReflectionClass('mysqli_stmt');
            $method = $reflectionObj->getMethod('bind_param');
            $method->invokeArgs($statement, $bindings);
        }
        $statement->execute();
        $this->bindings = [];
        $this->placeholders = [];

        return $statement;
    }

    public function fetchInto($className)
    {
        $results = [];
        $this->resultSet = $this->statement->get_result();
        while ($object = $this->resultSet->fetch_object($className))
        {
            $results[] = $object;
        }
        return $this->results = $results;
    }

    public function beginTransaction()
    {
        $this->conn->begin_transaction();
    }

    public function affected()
    {
        $this->statement->store_result();
        return $this->statement->affected_rows;
    }

    //----------------------------------------- Internal Methods -----------------------------------------------//
    private function parseBinding(array $params)
    {
        $bindings = [];
        $count = count($params);
        if($count === 0){
            return $this->bindings;
        }

        $bindingsTypes = $this->parseBindingTypes();
        $bindings[] = &$bindingsTypes;
        for($index = 0;$index<$count; $index++)
        {
            $bindings[] = &$params[$index];
        }
        return $bindings;
    }

    private function parseBindingTypes()
    {
        $bindingsTypes = [];
        foreach ($this->bindings as $binding) {
            if(is_int($binding)){
                $bindingsTypes[] = self::PARAM_TYPE_INT;
            }
            if(is_string($binding)){
                $bindingsTypes[] = self::PARAM_TYPE_STRING;
            }
            if(is_float($binding)){
                $bindingsTypes[] = self::PARAM_TYPE_DOUBLE;
            }
        }
        return implode('', $bindingsTypes);
    }



}