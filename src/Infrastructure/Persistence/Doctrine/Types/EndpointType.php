<?php

namespace Notifications\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Notifications\Domain\Model\Subscriber\Endpoint;

class EndpointType extends Type
{
    public const TYPE_NAME = "endpoint";
    public const MAX_VARCHAR_LENGTH = 255;

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof Endpoint) {
            throw new \InvalidArgumentException('Invalid type for endpoint conversion.');
        }
        return $value->__toString();
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $length = self::MAX_VARCHAR_LENGTH;
        return "VARCHAR($length)";
    }

    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Endpoint($value);
    }
}
