<?php

namespace Notifications\Tests\Integration\Insfrastructure\Lib;

use Notifications\Infrastructure\VapidGenerator;
use PHPUnit\Framework\TestCase;

class CreateVapidKeysForUser extends TestCase
{
    private const PRIVATE = 'privateKey';
    private const PUBLIC = 'publicKey';

    protected VapidGenerator $generatorKeys;
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
        $this->assertNotEmpty($this->generatorKeys[self::PUBLIC]);
        $this->assertGreaterThanOrEqual(86, strlen($this->generatorKeys[self::PUBLIC]));
    }

    public function testContentVapidPrivateKeys(): void
    {
        $this->assertNotEmpty($this->generatorKeys[self::PRIVATE]);
        $this->assertGreaterThanOrEqual(42, strlen($this->generatorKeys[self::PRIVATE]));
    }
}
