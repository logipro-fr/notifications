<?php

namespace Notifications\Tests\Domain\Services;

use Notifications\Domain\Services\KeyGeneratorStrategy;
use PHPUnit\Framework\TestCase;

class KeyGenFakeTest extends TestCase
{
    protected KeyGeneratorStrategy $generatorKey;

    protected function setUp(): void
    {
        $this->generatorKey = new KeyGenFake();
    }

    public function testGenerateACoupleOfKey(): void
    {
        $generatedKey = $this->generatorKey->generateACoupleOfKey();
        $this->assertNotEmpty($generatedKey);
    }

    public function testIfACoupleOfKeyIsCorrect(): void
    {

        $generatedKey = $this->generatorKey->generateACoupleOfKey();
        $public = $generatedKey[KeyGeneratorStrategy::PUBLICKEY];
        $this->assertIsString($public);
        $publicLengh = strlen($public);

        $private = $generatedKey[KeyGeneratorStrategy::PRIVATEKEY];
        $this->assertIsString($private);
        $privateLengh = strlen($private);

        $this->assertEquals(KeyGeneratorStrategy::PUBLIC_KEY_LENGTH, $publicLengh);
        $this->assertEquals(KeyGeneratorStrategy::PRIVATE_KEY_LENGTH, $privateLengh);
    }
}
