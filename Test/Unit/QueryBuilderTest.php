<?php
namespace Test\Unit;

use App\Database\PDOConnection;
use App\Database\QueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    /**
     * @var QueryBuilder $queryBuilder
     */
    private $queryBuilder;

    protected function setUp()
    {
        $pdo = new PDOConnection(Config::get('database', 'pdo'), ['db_name' =>  'bug_app_testing']);
        $this->queryBuilder = new QueryBuilder(
            $pdo->connect()
        );
        parent::setUp();
    }


    public function testBindings()
    {
        $query = $this->queryBuilder->where('id', 7)->where('report_type', '>=', '100');
        self::assertIsArray($query->getPlaceholders());
        self::assertIsArray($query->getBindings());

        var_dump($query->getPlaceholders());
        exit();
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