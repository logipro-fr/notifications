<?php

namespace Notifications\Tests\Insfrastructure;

use Notifications\Domain\Services\KeyGeneratorStrategy;
use Notifications\Infrastructure\Keys\VapidGenerator;
use Notifications\Tests\Domain\Services\KeyGenFakeTest;

class VapidGeneratorTest extends KeyGenFakeTest
{
    protected KeyGeneratorStrategy $generatorKey;

    protected function setUp(): void
    {
        $this->generatorKey = new VapidGenerator();
    }

    public function testCreate(): void
    {
        $generatedKey = $this->generatorKey->generateACoupleOfKey();
        $this->assertNotEmpty($generatedKey);
    }
}
