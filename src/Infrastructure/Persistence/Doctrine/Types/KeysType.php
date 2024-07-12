<?php

namespace Notifications\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Notifications\Domain\Entity\Subscriber\Keys;

class KeysType extends Type
{
    public const TYPE_NAME = 'keys';
    public function getName(): string
    {
        return self::TYPE_NAME;
    }


    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Keys) {
            return json_encode([
                'auth' => $value->getAuthKey(),
                'p256dh' => $value->getEncryptKey(),
            ]);
        }

        return null;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $length = 255;
        return "VARCHAR($length)";
    }
}
