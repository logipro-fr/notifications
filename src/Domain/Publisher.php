<?php

namespace Notifications\Domain;

class Publisher
{
    private mixed $publicKey;

    /** @var array<Subscriber> */
    protected array $subscribers = [];

    public function __construct(public string $name, private KeyGeneratorStrategy $keyGenerator)
    {
        $keys = $this->keyGenerator->generateACoupleOfKey();
        $this->publicKey = $keys['publicKey'];
    }

    public function getPublicKey(): mixed
    {
        return $this->publicKey;
    }

    public function getTargetName(): string
    {
        return $this->name;
    }

    public function subscribe(Subscriber $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }
}
