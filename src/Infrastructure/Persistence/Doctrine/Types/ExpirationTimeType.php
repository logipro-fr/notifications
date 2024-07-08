<?php

namespace Notifications\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ExpirationTimeType extends Type
{
    public const TYPE_NAME = "expirationTime";
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return serialize($value);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'text';
    }

    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return unserialize($value);
    }
}
