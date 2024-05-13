<?php

namespace Notifications\Tests\Domain;

use Notifications\Domain\KeyGeneratorStrategy;
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
        $public = strlen($generatedKey[KeyGeneratorStrategy::PUBLICKEY]);
        $private = strlen($generatedKey[KeyGeneratorStrategy::PRIVATEKEY]);

        $this->assertEquals(KeyGeneratorStrategy::PUBLIC_KEY_LENGTH, $public);
        $this->assertEquals(KeyGeneratorStrategy::PRIVATE_KEY_LENGTH, $private);
    }
}
