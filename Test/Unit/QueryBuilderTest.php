<?php
namespace Test\Unit;

use App\Database\MySQLiConnection;
use App\Database\MySQLiQueryBuilder;
use App\Database\PDOConnection;
use App\Database\PDOQueryBuilder;
use App\Database\QueryBuilder;
use App\Helpers\Config;
use App\Helpers\DbQueryBuilderFactory;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    /**
     * @var QueryBuilder $queryBuilder
     */
    private $queryBuilder;

    /**
     * @return mixed
     */
    public function insertIntoTable()
    {
        $data = [
            'report_type' => 'Report Type 1',
            'message' => 'This is a dummy message',
            'email' => 'support@devscreencast',
            'link' => 'https://link.com',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $id = $this->queryBuilder->table('reports')->create($data);
        return $id;
    }

    protected function setUp()
    {
        $this->queryBuilder = DbQueryBuilderFactory::make('database', 'mysqli', ['db_name' => 'bug_app_testing']);
        $this->queryBuilder->beginTransaction();
        parent::setUp();
    }


    protected function tearDown()
    {
        $this->queryBuilder->rollback();
        unset($this->queryBuilder);
        parent::tearDown();
    }

    public function testItCanCreateRecords()
    {
        $id = $this->insertIntoTable();
        self::assertNotNull($id);
    }

    public function testItCanPerformRawQuery()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->raw('SELECT * FROM reports')->get();
        self::assertNotNull($result);
    }

    public function testItCanPerformSelectQuery()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->table('reports')
            ->select('*')->where('id',$id)->runQuery()->first();

        self::assertSame($id, $result->id);
        self::assertNotNull($result);
    }

    public function testItCanPerformSelectQueryWithMultipleWhereClause()
    {
        $id = $this->insertIntoTable();
        $results = $this->queryBuilder->table('reports')
            ->select('*')->where('id',$id)
            ->where('report_type','=', 'Report Type 1')->runQuery()->first();
        self::assertNotNull($results);
        self::assertSame($id, $results->id);
        self::assertSame('Report Type 1', $results->report_type);
    }

    public function testItCanFindById()
    {
        $id = $this->insertIntoTable();
        $results = $this->queryBuilder->table('reports')->select('id, report_type')->find($id);
        self::assertNotNull($results);
        self::assertSame($id, $results->id);
        self::assertSame('Report Type 1', $results->report_type);
    }

    public function testItCanFindOneByGivingValues()
    {
        $id = $this->insertIntoTable();
        $results = $this->queryBuilder->table('reports')
            ->select('*')->findOneBy('report_type', 'Report Type 1');
        self::assertNotNull($results);
        self::assertSame($id, $results->id);
        self::assertSame('Report Type 1', $results->report_type);
    }

    public function testItCanUpdateGivenRecord()
    {
        $id = $this->insertIntoTable();
        $count = $this->queryBuilder->table('reports')->update(['report_type' => 'Report Type 2'])
            ->where('id', $id)->runQuery()->affected();

        self::assertEquals(1, $count);
        $results = $this->queryBuilder->select('*')->findOneBy('report_type', 'Report Type 2');
        self::assertNotNull($results);
        self::assertSame($id, $results->id);
        self::assertSame('Report Type 2', $results->report_type);
    }


    public function testItCanDeleteGivenId()
    {
        $id = $this->insertIntoTable();
        $count = $this->queryBuilder->table('reports')->delete()->where('id', $id)->runQuery()->affected();
        self::assertEquals(1, $count);

        $result = $this->queryBuilder->select('*')->find($id);
        self::assertEquals('', $result);
    }
}