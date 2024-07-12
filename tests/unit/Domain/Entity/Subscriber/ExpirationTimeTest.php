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
    //public function testEqualsMethod(): void
    //{
    //    $time1 = new ExpirationTime("2024-12-31T23:59:59Z");
    //    $time2 = new ExpirationTime("2024-12-31T23:59:59Z");
    //    $time3 = new ExpirationTime();
//
//
    //    $this->assertTrue($time1->equals($time2));
    //    $this->assertFalse($time1->equals($time3));
    //}
}
