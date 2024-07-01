<?php

namespace Notifications\Tests\Infrastructure\Persistance\Doctrine\Types;

use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Infrastructure\Persistence\Doctrine\Types\KeysEncryptType;
use PHPUnit\Framework\TestCase;

class KeysEncryptTypeTest extends TestCase
{
    public function testConvertToDatabaseValue(): void
    {
        $type = new KeysEncryptType();
        $keys = new Keys();

        $keysReflection = new \ReflectionClass(Keys::class);
        $vapidProperty = $keysReflection->getProperty('vapid');
        $vapidProperty->setAccessible(true);
        $vapidProperty->setValue($keys, ['publicKey' => 'test_public_key', 'privateKey' => 'test_private_key']);

        $result = $type->convertToDatabaseValue(
            $keys,
            $this->createMock(\Doctrine\DBAL\Platforms\AbstractPlatform::class)
        );

        $this->assertEquals('{"publicKey":"test_public_key","privateKey":"test_private_key"}', $result);
    }
}
