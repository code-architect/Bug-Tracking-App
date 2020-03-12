<?php
namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exception\NotFoundException;
use http\Exception\InvalidArgumentException;
use phpDocumentor\Reflection\Types\Self_;

 class QueryBuilder
{
    use Query;

    protected $conn;    // pdo or mysqli
    protected $table;
    protected $statement;
    protected $fields;
    protected $placeholders;
    protected $bindings;
    protected $operation = self::DML_TYPE_SELECT; //dml statement like SELECT, UPDATE, DELETE etc

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
        $query = $this->prepare($this->getQuery($this->operation));
        $this->statement = $this->execute($query);
        return $this;
    }

    //---------------------------------- CURD & Database Methods ---------------------------------------//

    public function select(string $fields = self::COLUMNS)
    {
        $this->operation = self::DML_TYPE_SELECT;
        $this->fields = $fields;
        return $this;
    }

    public function create(array $data)
    {

    }

    public function update(array $data)
    {

    }

    public function delete()
    {

    }

    public function raw($query)
    {

    }

    public function find($id)
    {

    }

    public function findOneBy(string $field, $value)
    {

    }

    public function first()
    {
        
    }


    //----------------------------------------------- Internals Methods -----------------------------------//
    private function passWhere(array $conditions, string $operator)
    {
        foreach ($conditions as $column => $value)
        {
            $this->placeholders[] = sprintf('%s %s %s', $column, $operator, self::PLACEHOLDER);
            $this->bindings[] = $value;
        }
        return $this;
    }

    //----------------------------------------------- Abstract Methods -----------------------------------//
    abstract public function get();
    abstract public function count();
    abstract public function lastInsertedId();
    abstract public function prepare($query);
    abstract public function execute($statement);
    abstract public function fetchInto($className);


}