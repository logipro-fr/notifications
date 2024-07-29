<?php

namespace Notifications\Tests\Infrastructure\Persistence\Doctrine\Types;

use Notifications\Infrastructure\Persistence\Doctrine\Types\EndpointType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Notifications\Domain\Model\Subscriber\Endpoint;
use PHPUnit\Framework\TestCase;

class EndpointTypeTest extends TestCase
{
    /** @var Type */
    private $type;

    /** @var AbstractPlatform|\PHPUnit\Framework\MockObject\MockObject */
    private $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('endpoint')) {
            Type::addType('endpoint', EndpointType::class);
        }

        $this->type = Type::getType('endpoint');
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    public function testGetName(): void
    {
        $this->assertEquals('endpoint', $this->type->getName());
    }

    public function testConvertToDatabaseValue(): void
    {
        $value = new Endpoint('https://example.com');
        $expected = 'https://example.com';

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($expected, $this->type->convertToDatabaseValue($value, $platform));
    }

    public function testConvertToPHPValue(): void
    {
        $value = 'https://example.com';
        $expected = new Endpoint($value);
        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($expected, $this->type->convertToPHPValue($value, $platform));
    }

    public function testGetSQLDeclaration(): void
    {
        $column = [];
        $expected = 'VARCHAR(255)';

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($expected, $this->type->getSQLDeclaration($column, $platform));
    }

    public function testConvertToDatabaseValueInvalidType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type for endpoint conversion.');

        $invalidValue = 'invalid_endpoint';
        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->type->convertToDatabaseValue($invalidValue, $platform);
    }
}
