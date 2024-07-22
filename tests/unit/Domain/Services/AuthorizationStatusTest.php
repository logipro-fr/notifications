<?php

use Notifications\Domain\Services\AuthorizationStatus;
use PHPUnit\Framework\TestCase;

class AuthorizationStatusTest extends TestCase
{
    public function testCanSetAndGetAuthorizationStatus(): void
    {
        $status = new AuthorizationStatus(false);
        $this->assertFalse($status->isAuthorized());

        $status->setAuthorization(true);
        $this->assertTrue($status->isAuthorized());
    }
}
