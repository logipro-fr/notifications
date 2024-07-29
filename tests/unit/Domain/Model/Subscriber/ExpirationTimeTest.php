<?php

namespace Notifications\Tests\Domain\Model\Subscriber;

use Notifications\Domain\Model\Subscriber\ExpirationTime;
use PHPUnit\Framework\TestCase;

class ExpirationTimeTest extends TestCase
{
    public function testGetExpirationTime(): void
    {
        $expirationTime = new ExpirationTime("");
        $this->assertEquals("", $expirationTime->__toString());
    }
}
