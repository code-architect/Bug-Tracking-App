<?php
namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exception\NotFoundException;
use phpDocumentor\Reflection\Types\Self_;

abstract class QueryBuilder
{
    protected $conn;    // pdo or mysqli
    protected $table;
    protected $statement;
    protected $fields;
    protected $placeholders;
    protected $bindings;
    protected $operation; //dml statement like SELECT, UPDATE, DELETE etc

    const OPERATORS = ['=', '>=', '>', '<=', '<', '<>'];
    const PLACEHOLDER = '?';
    const COLUMNS = '*';
    const DML_TYPE_SELECT = 'SELECT';
    const DML_TYPE_INSERT = 'INSERT';
    const DML_TYPE_UPDATE = 'UPDATE';
    const DML_TYPE_DELETE = 'DELETE';

    public function __construct(DatabaseConnectionInterface $connection)
    {
        $this->conn = $connection->getConnection();
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function where($column, $operator = self::OPERATORS[0], $value = null)
    {
        if(!in_array($operator, self::OPERATORS)){
            if($value === null){
                $value = $operator;
                $operator = self::OPERATORS[0];
            }else{
                throw new NotFoundException('Operator is not valid', ['operator' => $operator]);
            }
        }
        $this->passWhere([$column => $value], $operator);
        return $this;
    }


    //----------------------------------------------- Internals Methods -----------------------------------//
    protected function passWhere(array $conditions, string $operator)
    {
        
    }
}