<?php

namespace Notifications\Domain;

interface KeyGeneratorStrategy
{
    public const PUBLIC_KEY_LENGTH = 87;
    public const PUBLICKEY = 'publicKey';
    public const PRIVATE_KEY_LENGTH = 43;
    public const PRIVATEKEY = 'privateKey';


    /**
     * @return array<mixed>
     */
    public function generateACoupleOfKey(): array;
}
