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
}