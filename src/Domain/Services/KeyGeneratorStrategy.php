<?php

namespace Notifications\Domain\Services;

interface KeyGeneratorStrategy
{
    public const PUBLIC_KEY_LENGTH = 87;
    public const PUBLICKEY = 'publicKey';
    public const PRIVATE_KEY_LENGTH = 43;
    public const PRIVATEKEY = 'privateKey';


    /**
     * @return array<string>
     */
    public function generateACoupleOfKey(): array;
}
