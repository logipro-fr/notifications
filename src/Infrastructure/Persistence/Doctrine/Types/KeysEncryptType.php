<?php

namespace Notifications\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Notifications\Domain\Entity\Subscriber\Keys;

class KeysEncryptType extends Type
{
    public const TYPE_NAME = 'keys';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof Keys) {
            return $value->__toString();
        }

        throw new \InvalidArgumentException("Invalid type provided for convertToDatabaseValue");
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Keys
    {
        throw new \Exception("Method not implemented");
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($column);
    }
}
