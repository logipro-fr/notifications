<?php

namespace Notifications\Tests\Domain\Model\Notification;

use Notifications\Domain\Model\Notification\Action;
use Notifications\Domain\Model\Notification\Description;
use Notifications\Domain\Model\Notification\Icon;
use Notifications\Domain\Model\Notification\Notification;
use Notifications\Domain\Model\Notification\Title;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    public function testGetSubscriberValues(): void
    {
        $title = new Title("Notification Title");
        $description = new Description("Notification Description");
        $action = new Action("");
        $icon = new Icon("");

        $notification = new Notification(
            $title,
            $description,
            $action,
            $icon
        );
        $this->assertSame($title, $notification->getTitle());
        $this->assertSame($description, $notification->getDescription());
        $this->assertSame($action, $notification->getAction());
        $this->assertSame($icon, $notification->getIcon());
    }
}
