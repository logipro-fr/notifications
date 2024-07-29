<?php

namespace Notifications\Tests\Infrastructure;

use Notifications\Domain\Services\KeyGeneratorStrategy;
use Notifications\Infrastructure\Keys\VapidGenerator;
use Notifications\Tests\Domain\Services\KeyGenFakeTest;

class VapidGeneratorTest extends KeyGenFakeTest
{
    public function testCreate(): void
    {
        $generatedKey = (new VapidGenerator())->generateACoupleOfKey();
        $this->assertNotEmpty($generatedKey);
    }
}
