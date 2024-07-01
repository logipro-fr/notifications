<?php

namespace Notifications\Tests\Infrastructure;

use PHPUnit\Framework\TestCase;
use Notifications\Infrastructure\Shared\CurrentWorkDirPath;

class CurrentWorkDirPathTest extends TestCase
{
    private const MY_CURRENT_WORKING_DIR = '/home/myfolder';

    private ?string $d_env;
    private ?string $env;

    public function setUp(): void
    {
        $this->d_env = isset($_ENV['PWD']) ? $_ENV['PWD'] : null;
        $this->env = getenv('PWD') == false ? null : getenv('PWD');
    }

    public function tearDown(): void
    {
        if ($this->d_env == null) {
            unset($_ENV['PWD']);
        } else {
            $_ENV['PWD'] = $this->d_env;
        }

        if ($this->env == null) {
            putenv('PWD');
        } else {
            putenv('PWD=' . $this->env);
        }
    }

    public function testGetFullPathWithoutENV(): void
    {
        unset($_ENV['PWD']);
        putenv('PWD');
        $this->assertFalse(isset($_ENV['PWD']));
        $this->assertFalse(getenv('PWD'));

        $path = CurrentWorkDirPath::getPath();
        $this->assertEquals(getcwd(), $path);
    }

    public function testGetFullPathWithEnv(): void
    {
        $_ENV['PWD'] = self::MY_CURRENT_WORKING_DIR;
        $this->assertEquals(self::MY_CURRENT_WORKING_DIR, $_ENV['PWD']);
        putenv('PWD=' . self::MY_CURRENT_WORKING_DIR);
        $this->assertEquals(self::MY_CURRENT_WORKING_DIR, getenv('PWD'));

        $path = CurrentWorkDirPath::getPath();
        $this->assertEquals(self::MY_CURRENT_WORKING_DIR, $path);
    }

    public function testGetFullPathOnlygetenv(): void
    {
        unset($_ENV['PWD']);
        $this->assertFalse(isset($_ENV['PWD']));
        putenv('PWD=' . self::MY_CURRENT_WORKING_DIR);
        $this->assertEquals(self::MY_CURRENT_WORKING_DIR, getenv('PWD'));

        $path = CurrentWorkDirPath::getPath();
        $this->assertEquals(self::MY_CURRENT_WORKING_DIR, $path);
    }
}
