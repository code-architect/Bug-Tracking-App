<?php
namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use http\Exception\InvalidArgumentException;
use phpDocumentor\Reflection\Types\Self_;

 abstract class QueryBuilder
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
                throw new \App\Exception\InvalidArgumentException('Operator is not valid', ['operator' => $operator]);
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
        $this->fields = '`' . implode('`, `', array_keys($data)) . '`';
        foreach ($data as $value){
            $this->placeholders[] = self::PLACEHOLDER;
            $this->bindings[] = $value;
        }
        $query = $this->prepare($this->getQuery(self::DML_TYPE_INSERT));
        $this->statement = $this->execute($query);

        return $this->lastInsertedId();
    }

    public function update(array $data)
    {
        $this->fields = [];
        $this->operation = self::DML_TYPE_UPDATE;
        foreach ($data as $column => $value)
        {
            $this->fields[] = sprintf('%s%s%s', $column, self::OPERATORS[0], "'$value'");
        }
        return $this;
    }

    public function delete()
    {
        $this->operation = self::DML_TYPE_DELETE;
        return $this;
    }

    public function raw($query)
    {
        $query = $this->prepare($query);
        $this->statement = $this->execute($query);
        return $this;
    }

    public function find($id)
    {
        return $this->where('id', $id)->first();
    }

    public function findOneBy(string $field, $value)
    {
        return $this->where($field, $value)->first();
    }

    public function first()
    {
        return $this->count() ? $this->get()[0] : "";
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