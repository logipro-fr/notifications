<?php

namespace Notifications\Domain\Entity\Subscriber;

class Keys
{
    private string $auth;
    private string $p256dh;

    public function __construct(string $auth, string $p256dh)
    {
        $this->auth = $auth;
        $this->p256dh = $p256dh;
    }

      /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'auth' => $this->getAuthKey(),
            'p256dh' => $this->getEncryptKey(),
        ];
    }

    public function getAuthKey(): string
    {
        return $this->auth;
    }

    public function getEncryptKey(): string
    {
        return $this->p256dh;
    }
}
