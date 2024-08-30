<?php

namespace Notifications\Tests\Domain\Model\Subscriber;

use Notifications\Domain\Model\Subscriber\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testStatus(): void
    {
        $this->assertInstanceOf(Status::class, Status::SUBSCRIBED);
    }
}
