<?php

namespace Notifications\Tests\Insfrastructure;

use Notifications\Domain\KeyGeneratorStrategy;
use Notifications\Infrastructure\VapidGenerator;
use Notifications\Tests\Domain\KeyGenFakeTest;

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
