<?php

namespace Notifications\Tests\Infrastructure\Persistance\Doctrine\Types;

use Notifications\Infrastructure\Persistence\Doctrine\Types\EndpointType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
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
        $value = ['key' => 'value'];
        $expected = serialize($value);

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($expected, $this->type->convertToDatabaseValue($value, $platform));
    }

    public function testConvertToPHPValue(): void
    {
        $value = ['key' => 'value'];
        $serialized = serialize($value);

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($value, $this->type->convertToPHPValue($serialized, $platform));
    }

    public function testGetSQLDeclaration(): void
    {
        $column = [];
        $expected = 'text';

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($expected, $this->type->getSQLDeclaration($column, $platform));
    }
}
