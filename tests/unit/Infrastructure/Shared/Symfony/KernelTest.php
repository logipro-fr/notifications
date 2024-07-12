<?php

namespace Notifications\Tests\Infrastructure\Shared\Symfony;

use PHPUnit\Framework\TestCase;
use Notifications\Infrastructure\Shared\Symfony\Kernel;

class KernelTest extends TestCase
{
    public function testConstructWithDebug(): void
    {
        $kernel = new Kernel("test", true);
        $this->assertInstanceOf(Kernel::class, $kernel);
        $this->assertTrue($kernel->isDebug());
    }

    public function testConstructWithoutDebug(): void
    {
        $kernel = new Kernel("test", false);
        $this->assertInstanceOf(Kernel::class, $kernel);
        $this->assertFalse($kernel->isDebug());
    }
}
