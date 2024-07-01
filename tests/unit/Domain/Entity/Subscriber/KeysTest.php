<?php

namespace Notifications\Tests\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Services\KeyGeneratorStrategy;
use PHPUnit\Framework\TestCase;

class KeysTest extends TestCase
{
    /** @var Keys */
    private $keys;

    protected function setUp(): void
    {
        $this->keys = new Keys();
    }

    public function testGenerateACoupleOfKey(): void
    {
        $generatedKeys = $this->keys->generateACoupleOfKey();

        $this->assertArrayHasKey('publicKey', $generatedKeys);
        $this->assertArrayHasKey('privateKey', $generatedKeys);
    }

    public function testGetVAPIDKeys(): void
    {
        $this->keys->generateACoupleOfKey();

        $retrievedKeys = $this->keys->getVAPIDKeys();

        $this->assertArrayHasKey('publicKey', $retrievedKeys);
        $this->assertArrayHasKey('privateKey', $retrievedKeys);
    }
}
