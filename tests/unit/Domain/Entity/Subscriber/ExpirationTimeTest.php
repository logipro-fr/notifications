<?php

namespace Notifications\Tests\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use PHPUnit\Framework\TestCase;

class ExpirationTimeTest extends TestCase
{
    public function testConstructorGeneratesUniqueIdWhenNotProvided(): void
    {
        $time1 = new ExpirationTime();
        $time2 = new ExpirationTime();

        $this->assertNotEmpty($time1->__toString());
        $this->assertNotEmpty($time2->__toString());

        $this->assertNotEquals($time1->__toString(), $time2->__toString());
    }

    public function testEqualsMethod(): void
    {
        $time1 = new ExpirationTime();
        $time2 = new ExpirationTime();
        $time3 = new ExpirationTime($time1->__toString());


        $this->assertTrue($time1->equals($time1));
        $this->assertTrue($time1->equals($time3));

        $this->assertFalse($time1->equals($time2));
    }

    public function testToStringMethod(): void
    {
        $time = new ExpirationTime();
        $idString = $time->__toString();

        // Ensure __toString() returns the ID string
        $this->assertEquals($idString, (string)$time);
    }
}
