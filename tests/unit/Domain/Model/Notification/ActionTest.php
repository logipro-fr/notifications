<?php

namespace Notifications\Tests\Domain\Model\Notification;

use Notifications\Domain\Model\Notification\Action;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    public function testConstructWithContent(): void
    {
        $url = "pathToImage";
        $action = new Action($url);

        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals($url, (string)$action);
    }

    public function testConstructWithEmptyContent(): void
    {
        $url = "";
        $action = new Action($url);

        $this->assertInstanceOf(Action::class, $action);
        $this->assertSame("", (string)$action);
    }

    public function testToString(): void
    {
        $url = "www.nextsign.fr";
        $action = new Action($url);

        $this->assertEquals($url, $action->__toString());
    }
}
