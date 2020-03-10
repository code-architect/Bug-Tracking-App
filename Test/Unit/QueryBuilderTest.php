<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    private $queryBuilder;

    protected function setUp()
    {
        $this->queryBuilder = new QueryBuilder();
        parent::setUp();
    }

    protected function tearDown()
    {
        unset($this->queryBuilder);
    }

    public function testItCanCreateRecords()
    {
        $id = $this->queryBuilder->table('reports')->create($data);
        self::assertNotNull($id);
    }

    public function testItCanPerformRawQuery()
    {
        $result = $this->queryBuilder->raw('SELECT * FROM reports');
        self::assertNotNull($result);
    }

    public function testItCanPerformSelectQuery()
    {
        $result = $this->queryBuilder->table('reports')->select('*')->where('id',1)->first();
        self::assertSame(1,(int)$result->id);
        self::assertNotNull($result);
    }

    public function testItCanPerformSelectQueryWithMultipleWhereClause()
    {
        $results = $this->queryBuilder->table('reports')
            ->select('*')->where('id',1)->where('report_type','=', 'Report Type 1')->first();
        self::assertNotNull($results);
        self::assertSame(1,(int)$results->id);
        self::assertSame('Report Type 1', $results->report_type);
    }
}