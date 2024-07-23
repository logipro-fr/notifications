<?php

namespace Notifications\Tests\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use PHPUnit\Framework\TestCase;

class ExpirationTimeTest extends TestCase
{
    public function testGetExpirationTime(): void
    {
        $expirationTime = new ExpirationTime("");
        $this->assertEquals("", $expirationTime->__toString());
    }
}
