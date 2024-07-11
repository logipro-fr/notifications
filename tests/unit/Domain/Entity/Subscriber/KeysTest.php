<?php

namespace Notifications\Tests\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Subscriber\Keys;
use PHPUnit\Framework\TestCase;

class KeysTest extends TestCase
{
    public function testGetAuthKey()
    {
        $auth = 'authKey123';
        $p256dh = 'encryptKey456';
        
        $keys = new Keys($auth, $p256dh);
        
        $this->assertEquals($auth, $keys->getAuthKey());
    }

    public function testGetEncryptKey()
    {
        $auth = 'authKey123';
        $p256dh = 'encryptKey456';
        
        $keys = new Keys($auth, $p256dh);
        
        $this->assertEquals($p256dh, $keys->getEncryptKey());
    }
}
