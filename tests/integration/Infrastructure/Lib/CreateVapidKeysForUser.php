<?php

namespace Notifications\Tests\Integration\Insfrastructure\Lib;

use Notifications\Infrastructure\Keys\VapidGenerator;
use PHPUnit\Framework\TestCase;

class CreateVapidKeysForUser extends TestCase
{
    private const PRIVATE = 'privateKey';
    private const PUBLIC = 'publicKey';

    protected VapidGenerator $generatorKeys;
    /** @var array<string> */
    protected array $generatedKeys;

    protected function setUp(): void
    {
        $this->generatorKeys = (new VapidGenerator());
        $this->generatedKeys = $this->generatorKeys->generateACoupleOfKey();
    }

    public function testCreateVapidPublicKeys(): void
    {
        $this->assertArrayHasKey(self::PUBLIC, $this->generatedKeys);
    }

    public function testCreateVapidPrivateKeys(): void
    {
        $this->assertArrayHasKey(self::PRIVATE, $this->generatedKeys);
    }

    public function testContentVapidPublicKeys(): void
    {
        $publicKey = $this->generatedKeys[self::PUBLIC];
        $this->assertIsString($publicKey);
        $this->assertNotEmpty($publicKey);
        $this->assertGreaterThanOrEqual(86, strlen($publicKey));
    }

    public function testContentVapidPrivateKeys(): void
    {
        $privateKey = $this->generatedKeys[self::PRIVATE];
        $this->assertIsString($privateKey);
        $this->assertNotEmpty($privateKey);
        $this->assertGreaterThanOrEqual(42, strlen($privateKey));
    }
}
