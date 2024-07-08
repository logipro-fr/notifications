<?php

namespace Notifications\Tests\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Subscriber\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testStatus(): void
    {
        $this->assertInstanceOf(Status::class, Status::SUBSCRIBED);
    }
}
