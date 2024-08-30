<?php

namespace Notifications\Tests\Domain\Model\Notification;

use Notifications\Domain\Model\Notification\Description;
use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    public function testConstructWithContent(): void
    {
        $content = "Notification Description";
        $description = new Description($content);

        $this->assertInstanceOf(Description::class, $description);
        $this->assertEquals($content, (string)$description);
    }

    public function testConstructWithEmptyContent(): void
    {
        $content = "";
        $description = new Description($content);

        $this->assertInstanceOf(Description::class, $description);
        $this->assertSame("", (string)$description);
    }

    public function testToString(): void
    {
        $content = "Notification Description";
        $description = new Description($content);

        $this->assertEquals($content, $description->__toString());
    }
}
