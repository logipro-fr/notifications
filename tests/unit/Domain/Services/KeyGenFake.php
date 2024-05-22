<?php

namespace Notifications\Tests\Domain\Services;

use Notifications\Domain\Services\KeyGeneratorStrategy;

class KeyGenFake implements KeyGeneratorStrategy
{
    /**
     * @return array<string>
     */
    public function generateACoupleOfKey(): array
    {
        $publicKey = openssl_random_pseudo_bytes(self::PUBLIC_KEY_LENGTH);
        $privateKey = openssl_random_pseudo_bytes(self::PRIVATE_KEY_LENGTH);

        return array(
            KeyGeneratorStrategy::PUBLICKEY => $publicKey,
            KeyGeneratorStrategy::PRIVATEKEY => $privateKey
        );
    }
}
