<?php

namespace Notifications\Tests\Domain\Services;

use Notifications\Domain\Services\StatusClient;
use PHPUnit\Framework\TestCase;

class StatusClientTest extends TestCase
{
    public function testIsSubscriber(): void
    {
        $client = new StatusClient();
        $client->setValue(true);
        $this->assertTrue($client->getValue());
    }

    public function testIsNotSubscriber(): void
    {
        $client = new StatusClient();
        $client->setValue(false);
        $this->assertFalse($client->getValue());
    }
}
