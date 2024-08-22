<?php

namespace Notifications\Tests\Domain\Model\Notification;

use Notifications\Domain\Model\Notification\Icon;
use PHPUnit\Framework\TestCase;

class IconTest extends TestCase
{
    public function testConstructWithContent(): void
    {
        $path = "pathToImage";
        $icon = new Icon($path);

        $this->assertInstanceOf(Icon::class, $icon);
        $this->assertEquals($path, $icon->getIcon());
    }

    public function testConstructWithEmptyContent(): void
    {
        $path = "";
        $icon = new Icon($path);

        $this->assertInstanceOf(Icon::class, $icon);
        $this->assertEmpty($icon->getIcon());
    }

    public function testToString(): void
    {
        $path = "Notification/path";
        $icon = new Icon($path);

        $this->assertEquals($path, $icon->getIcon());
    }
}
