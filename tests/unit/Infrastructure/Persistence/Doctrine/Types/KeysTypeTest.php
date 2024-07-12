<?php

namespace Notifications\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Notifications\Domain\Entity\Subscriber\AuthKey;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Infrastructure\Persistence\Doctrine\Types\KeysType;
use PHPUnit\Framework\TestCase;

class KeysTypeTest extends TestCase
{
    /** @var Type */
    private $type;

    /** @var AbstractPlatform|\PHPUnit\Framework\MockObject\MockObject */
    private $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('keys')) {
            Type::addType('keys', KeysType::class);
        }

        $this->type = Type::getType('keys');
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    public function testGetName(): void
    {
        $this->assertEquals('keys', $this->type->getName());
    }

    public function testConvertToDatabaseValue(): void
    {
        $value = new Keys('1234', '9876');
        $expected = json_encode(['auth' => '1234', 'p256dh' => '9876']);

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($expected, $this->type->convertToDatabaseValue($value, $platform));
    }

    public function testConvertToDatabaseNullValue(): void
    {
        $value = new Keys('1234', '9876');
        $expected = json_encode(['auth' => '1234', 'p256dh' => '9876']);

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($expected, $this->type->convertToDatabaseValue($value, $platform));
    }

    public function testConvertToDatabaseValueReturnsNull(): void
    {
        $value = null;

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertNull($this->type->convertToDatabaseValue($value, $platform));
    }

    public function testGetSQLDeclaration(): void
    {
        $column = [];
        $expected = 'VARCHAR(255)';

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($expected, $this->type->getSQLDeclaration($column, $platform));
    }
}
