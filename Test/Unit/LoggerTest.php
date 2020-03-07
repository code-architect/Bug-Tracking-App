<?php
namespace Test\Unit;

use App\Contracts\LoggerInterface;
use App\Exception\InvalidLogLevelArgument;
use App\Helpers\App;
use App\Logger\Logger;
use App\Logger\Loglevel;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    private $logger;
    protected function setUp()
    {
        $this->logger = new Logger();
        parent::setUp();
    }

    protected function tearDown()
    {
        unset($this->logger);
    }

    public function testItImplementsTheLoggerInterface()
    {
        self::assertInstanceOf(LoggerInterface::class, $this->logger);
    }

    public function testItCanCreateDifferentTypesOfLogLevels()
    {
        $app = new App();
        //Logging the the application
        $this->logger->info('Testing Info Logs');
        $this->logger->Error('Testing Error Logs');
        $this->logger->log(Loglevel::ALERT,'Testing Alert Logs');

        //creating a file and checking if file exists
        $fileName = sprintf("%s/%s-%s.log", $app->getLogPath(), 'local', date("j.n.Y"));
        self::assertFileExists($fileName);

        // checking if the log file contains the above given string
        $contentOfLogFile = file_get_contents($fileName);
        self::assertStringContainsString('Testing Info Logs', $contentOfLogFile);
        self::assertStringContainsString('Testing Error Logs', $contentOfLogFile);
        self::assertStringContainsString('Testing Alert Logs', $contentOfLogFile);

        // deleting the file and checking if the file has been deleted
        unlink($fileName);
        self::assertFileNotExists($fileName);
    }

    public function testIfThrowsInvalidLogLevelArgumentExceptionWhenGivenAWrongLogLevel()
    {
        self::expectException(InvalidLogLevelArgument::class);
        $this->logger->log('invalid', 'testing invalid log level');
    }
}