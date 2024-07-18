<?php

namespace Notifications\Tests\public ;

use Notifications\Infrastructure\Shared\Symfony\Kernel;
use Closure;
use PHPUnit\Framework\TestCase;

class indexTest extends TestCase
{
    public function testNoError(): void
    {
        $sut = include_once(getcwd() . '/src/public/index.php');
        $this->assertInstanceOf(Closure::class, $sut);
        $this->assertInstanceOf(Kernel::class, $sut(['APP_ENV' => 'test', 'APP_DEBUG' => false]));
    }
}