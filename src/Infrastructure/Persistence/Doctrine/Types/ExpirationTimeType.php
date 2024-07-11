<?php

namespace Notifications\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;

class ExpirationTimeType extends Type
{
    public const TYPE_NAME = "expirationTime";
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->__toString();
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return "text";
    }

    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new ExpirationTime();
    }
}
