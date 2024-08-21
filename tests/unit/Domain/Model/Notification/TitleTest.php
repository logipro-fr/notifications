<?php

namespace Notifications\Tests\Domain\Model\Notification;

use Notifications\Domain\Model\Notification\Title;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    public function testConstructWithContent(): void
    {
        $content = "Notification Title";
        $title = new Title($content);

        $this->assertInstanceOf(Title::class, $title);
        $this->assertEquals($content, (string)$title);
    }

    public function testConstructWithEmptyContent(): void
    {
        $content = "";
        $title = new Title($content);

        $this->assertInstanceOf(Title::class, $title);
        $this->assertSame("Default Title", (string)$title);
    }

    public function testToString(): void
    {
        $content = "Notification Title";
        $title = new Title($content);

        $this->assertEquals($content, $title->__toString());
    }
}
