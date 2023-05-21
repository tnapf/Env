<?php

namespace Tests\Tnapf\Env;

use PHPUnit\Framework\TestCase;
use Tnapf\Env\Env;

class TestEnv extends TestCase
{
    public function testCreatingFromString(): void
    {
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

        Env::destroy();
    }

    public function testCreatingFromFile(): void
    {
        $env = Env::createFromFile(__DIR__ . '/.test.env');

        $this->assertSame('root', $env->USERNAME);
        $this->assertSame('123456', $env->PASSWORD);
        $this->assertSame('127.0.0.1', $env->HOST);
        $this->assertSame('3306', $env->PORT);

        Env::destroy();
    }

    public function testCreatingFromNonExistingFile(): void
    {
        $this->expectException(\Exception::class);

        Env::createFromFile(__DIR__ . '/.non-existing.env');
    }
}
