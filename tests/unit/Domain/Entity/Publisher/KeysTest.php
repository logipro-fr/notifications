<?php

namespace Notifications\Tests\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Publisher\PublisherKeys;
use PHPUnit\Framework\TestCase;

class PublisherKeysTest extends TestCase
{
    /** @var PublisherKeys */
    private $keys;

    protected function setUp(): void
    {
        $this->keys = new PublisherKeys();
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
