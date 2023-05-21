<?php

namespace Tnapf\Env\Tests;

use Exception;
use LogicException;
use RuntimeException;
use PHPUnit\Framework\TestCase;
use Tnapf\Env\Env;

class TestEnv extends TestCase
{
    public function testCreatingFromString(): void
    {
        Env::destroy();

        $contents = <<<'ENV'
        USERNAME=root
        PASSWORD=123456
        HOST=127.0.0.1
        PORT=3306
        ENV;

        $env = Env::createFromString($contents);

        $this->assertSame('root', $env->USERNAME);
        $this->assertSame('123456', $env->PASSWORD);
        $this->assertSame('127.0.0.1', $env->HOST);
        $this->assertSame('3306', $env->PORT);
        $this->assertTrue(isset($env->USERNAME));
    }

    public function testTryingToCreateTwoInstances(): void
    {
        Env::destroy();
        Env::createFromString('');

        $this->expectException(LogicException::class);
        Env::createFromString('');
    }

    public function testCreatingFromBrokenFormat(): void
    {
        Env::destroy();
        $this->expectException(RuntimeException::class);
        Env::createFromString('USERNAME');
    }

    public function testCreatingWithBrokenName(): void
    {
        Env::destroy();
        $this->expectException(RuntimeException::class);
        Env::createFromString('PA$$word=123456');
    }

    public function testCreatingFromFile(): void
    {
        Env::destroy();
        Env::createFromFile(__DIR__.'/.test.env');

        $this->assertSame('root', Env::get()->USERNAME);
        $this->assertSame('123456', Env::get()->PASSWORD);
        $this->assertSame('127.0.0.1', Env::get()->HOST);
        $this->assertSame('3306', Env::get()->PORT);
    }

    public function testCreatingFromNonExistingFile(): void
    {
        Env::destroy();
        $this->expectException(Exception::class);
        Env::createFromFile(__DIR__.'/.non-existing.env');
    }

    public function testDebugInfo(): void
    {
        Env::destroy();
        $env = Env::createFromFile(__DIR__.'/.test.env');

        $props = [
            'USERNAME' => 'root',
            'PASSWORD' => '123456',
            'HOST' => '127.0.0.1',
            'PORT' => '3306',
        ];

        $this->assertSame($props, $env->__debugInfo());
    }

    public function testArrayAccess(): void
    {
        Env::destroy();
        $env = Env::createFromFile(__DIR__.'/.test.env');
        $env['TEST'] = 10;

        $this->assertSame('root', $env['USERNAME']);
        $this->assertSame('123456', $env['PASSWORD']);
        $this->assertSame('127.0.0.1', $env['HOST']);
        $this->assertSame('3306', $env['PORT']);
        $this->assertSame(10, $env['TEST']);

        $this->assertTrue(isset($env['USERNAME']));

        unset($env['USERNAME']);

        $this->assertFalse(isset($env['USERNAME']));
    }
}
