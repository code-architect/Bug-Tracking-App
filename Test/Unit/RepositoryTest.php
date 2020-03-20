<?php
declare(strict_types=1);
namespace Test\Unit;

use App\Database\QueryBuilder;
use App\Helpers\DbQueryBuilderFactory;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    /**
     * @var QueryBuilder $queryBuilder
     */
    private $queryBuilder;

    private $bugReportRepository;

    protected function setUp()
    {
        $this->queryBuilder = DbQueryBuilderFactory::make('database', 'mysqli', ['db_name' => 'bug_app_testing']);
        $this->queryBuilder->beginTransaction();
        $this->bugReportRepository = new BugReportRepository($this->queryBuilder);
        parent::setUp();
    }


    protected function tearDown()
    {
        $this->queryBuilder->rollback();
        unset($this->queryBuilder);
        parent::tearDown();
    }



    public function createBugReport():BugReport
    {
        $bugReport = new BugReport();
        $bugReport->setReportType('Type 2')->setLink('https://xyz-link.com')
            ->setMessage('This is a dummy Message')->setEmail('john@xyz.com');

        $newBugReport = $this->bugReportRepository->create($bugReport);
        return $newBugReport;
    }


    public function testItCanCreateRecordWithEntity()
    {
        $newBugReport = $this->createBugReport();

        self::assertInstanceOf(BugReport::class, $newBugReport);
        self::assertNotEmpty( $newBugReport->getId());
        self::assertSame('Type 2', $newBugReport->getReportType);
        self::assertSame('https://xyz-link.com', $newBugReport->getLink);
        self::assertSame('This is a dummy Message', $newBugReport->getMessage);
        self::assertSame('john@xyz.com', $newBugReport->getEmail);
    }


    public function testItCanUpdateAGivenEntity()
    {
        $newBugReport = $this->createBugReport();
        $bugReport = $this->bugReportRepository->find($newBugReport->getId());

        $bugReport->setMessage('This is from update method')->setLink('https://newlink.com/image.png');
        $updatedReport = $this->bugReportRepository->update($bugReport);

        self::assertInstanceOf(BugReport::class, $updatedReport);
        self::assertSame('This is from update method', $updatedReport->getReportType);
        self::assertSame('https://newlink.com/image.png', $updatedReport->getLink);
    }


    public function testItCanDeleteAGivenEntity()
    {
        $newBugReport = $this->createBugReport();
        $this->bugReportRepository->delete($newBugReport);
        $bugReport = $this->bugReportRepository->find($newBugReport->getId());

        self::assertNull($bugReport);
    }
}