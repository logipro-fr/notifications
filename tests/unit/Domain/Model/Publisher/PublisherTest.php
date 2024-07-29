<?php

namespace Notifications\Tests\Domain\Model\Publisher;

use Notifications\Domain\Model\Publisher\Publisher;
use PHPUnit\Framework\TestCase;

class PublisherTest extends TestCase
{
    protected function setUp(): void
    {
    }

    public function testPublisherInitialization(): void
    {
        $publisher = new Publisher('testName');

        $this->assertEquals('testName', $publisher->getTargetName());
    }
}
