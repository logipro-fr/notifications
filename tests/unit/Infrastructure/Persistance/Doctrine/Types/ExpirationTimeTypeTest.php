<?php

namespace Notifications\Tests\Infrastructure\Persistance\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Infrastructure\Persistence\Doctrine\Types\ExpirationTimeType;
use PHPUnit\Framework\TestCase;

class ExpirationTimeTypeTest extends TestCase
{
    /** @var Type */
    private $type;

    /** @var AbstractPlatform|\PHPUnit\Framework\MockObject\MockObject */
    private $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('expirationTime')) {
            Type::addType('expirationTime', ExpirationTimeType::class);
        }

        $this->type = Type::getType('expirationTime');
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    public function testGetName(): void
    {
        $this->assertEquals('expirationTime', $this->type->getName());
    }

    public function testConvertToDatabaseValue(): void
    {
        $value = new ExpirationTime('null');
        $expected = 'null';

        /** @var AbstractPlatform $platform */
        $platform = $this->platform;
        $this->assertEquals($expected, $this->type->convertToDatabaseValue($value, $platform));
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
