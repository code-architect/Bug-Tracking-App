<?php

namespace Test\Unit;

use App\Helpers\App;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testItCanGetInstanceOfApp()
    {
        static::assertInstanceOf(App::class, new App());
    }

    public function testItCanGetBasicAppDataSetFromAppClass()
    {
        $app = new App();
        self::assertTrue($app->isRunningFromConsole());
        self::assertSame('test', $app->getEnvironment());
        self::assertNotNull($app->getLogPath());
        $this->assertInstanceOf(\DateTime::class, $app->getServerTime());
    }
}