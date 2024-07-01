<?php

namespace Notifications\Domain\Entity\Publisher;

use Minishlink\WebPush\WebPush;
use Notifications\Domain\Services\KeyGeneratorStrategy;
use Notifications\Domain\Entity\Subscriber\Subscriber;

class Publisher
{
    private string $publicKey;
    private string $privateKey;
    private string $name;

    /** @var array<Subscriber> */
    protected array $subscribers = [];


    public function __construct(string $name, private KeyGeneratorStrategy $keyGenerator)
    {
        $this->name = $name;
        $keys = $this->keyGenerator->generateACoupleOfKey();
        $this->publicKey = $keys['publicKey'];
        $this->privateKey = $keys['privateKey'];
        $auth = [
            'VAPID' => [
                'subject' => $name, // can be a mailto: or your website address
                'publicKey' =>  $this->publicKey, // (recommended) uncompressed public key P-256 encoded in Base64-URL
                'privateKey' =>  $this->privateKey
            ],
        ];

        $webPush = new WebPush($auth);
        //$webPush->queueNotification(...);
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getTargetName(): string
    {
        return $this->name;
    }

    public function removePublicKey(): string
    {
        if ($this->publicKey !== "") {
            $this->publicKey = "";
            return "KeyRemoved";
        }
        return "";
    }
}
