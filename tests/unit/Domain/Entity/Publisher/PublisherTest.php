<?php

namespace Notifications\Tests\Domain\Entity\Publisher;

use Notifications\Domain\Services\KeyGeneratorStrategy;
use Notifications\Domain\Entity\Publisher\Publisher;
use PHPUnit\Framework\TestCase;

class PublisherTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject&KeyGeneratorStrategy */
    private $keyGeneratorMock;

    private const PUBLICKEY = 'BMtpkXoQX6GHZFTlCQUNF2I_kvWu4QcXyHJ2E-fnGz4Jle-MXtP8pD-lPaG7Gm27OVZUMVC87HiB8cvqP7o0YPQ';
    private const PRIVATEKEY = 'wG8isO8A1l7Hw3q2eq29rv7XaFdWgPBDR1cwWJCA7qM';

    protected function setUp(): void
    {
        $this->keyGeneratorMock = $this->createMock(KeyGeneratorStrategy::class);
        $this->keyGeneratorMock->method('generateACoupleOfKey')
            ->willReturn([
                'publicKey' => self::PUBLICKEY,
                'privateKey' => self::PRIVATEKEY,
            ]);
    }

    public function testPublisherInitialization(): void
    {
        $publisher = new Publisher('testName', $this->keyGeneratorMock);

        $this->assertEquals(self::PUBLICKEY, $publisher->getPublicKey());
        $this->assertEquals('testName', $publisher->getTargetName());
    }

    public function testRemovePublicKey(): void
    {
        $publisher = new Publisher('testName', $this->keyGeneratorMock);

        $this->assertEquals(self::PUBLICKEY, $publisher->getPublicKey());

        $result = $publisher->removePublicKey();

        $this->assertEquals('KeyRemoved', $result);
        $this->assertEquals('', $publisher->getPublicKey());
    }

    public function testRemovePublicKeyWhenAlreadyRemoved(): void
    {
        $publisher = new Publisher('testName', $this->keyGeneratorMock);

        $publisher->removePublicKey();
        $result = $publisher->removePublicKey();

        $this->assertEquals('', $result);
        $this->assertEquals('', $publisher->getPublicKey());
    }
}
