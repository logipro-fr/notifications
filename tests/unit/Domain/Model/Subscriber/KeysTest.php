<?php

namespace Notifications\Tests\Domain\Model\Subscriber;

use Notifications\Domain\Model\Subscriber\Keys;
use PHPUnit\Framework\TestCase;

class KeysTest extends TestCase
{
    public function testGetAuthKey(): void
    {
        $auth = 'authKey123';
        $p256dh = 'encryptKey456';

        $keys = new Keys($auth, $p256dh);

        $this->assertEquals($auth, $keys->getAuthKey());
    }

    public function testGetEncryptKey(): void
    {
        $auth = 'authKey123';
        $p256dh = 'encryptKey456';

        $keys = new Keys($auth, $p256dh);

        $this->assertEquals($p256dh, $keys->getEncryptKey());
    }

    public function testGetAllKeys(): void
    {
        $auth = 'authKey123';
        $p256dh = 'encryptKey456';

        $keys = new Keys($auth, $p256dh);
        $array = $keys->toArray();
        $this->assertEquals($auth, $array['auth']);
        $this->assertEquals($p256dh, $array['p256dh']);
    }
}
