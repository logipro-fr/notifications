<?php

namespace Notifications\Tests\Infrastructure;

use PHPUnit\Framework\TestCase;
use Minishlink\WebPush\WebPush;
use Notifications\Domain\Exceptions\BadDataClassException;
use Notifications\public\Send;

class SendTest extends TestCase
{
    private const NOTIFICATION_FILETEST = 'notification_test.json';
    private const SUBSCRIPTION_FILETEST = 'subscription_test.json';

    protected Send $send;

    protected function setUp(): void
    {

        $this->send = new Send();
    }


    public function testSendNotificationMethod(): void
    {
        ob_start();
        $this->send->sendNotification();

        $output = strval(ob_get_clean());
        $this->assertStringContainsString('Request', $output);
        $this->assertStringContainsString('Response', $output);
    }

    public function testSendNotification(): void
    {
        $subscriptionData = $this->send->getSubscriptionData(__DIR__, self::SUBSCRIPTION_FILETEST);
        $notificationData = $this->send->getNotificationData(__DIR__, self::NOTIFICATION_FILETEST);
        $notificationDataJson = $this->send->encodeNotificationData($notificationData);
        $options = ['TTL' => 5000];
        $report = $this->send->sendOneNotification($subscriptionData, $notificationDataJson, $options);
        $this->assertNotNull($report);
    }

    public function testValidSubscriptionData(): void
    {
        $subscriptionData = $this->send->getSubscriptionData(__DIR__, self::SUBSCRIPTION_FILETEST);
        $this->assertIsArray($subscriptionData);
    }

    public function testValidNotificationData(): void
    {
        $notificationData = $this->send->getNotificationData(__DIR__, self::NOTIFICATION_FILETEST);
        $this->assertIsArray($notificationData);
    }

    public function testNotificationDataEncoding(): void
    {
        $notificationData = $this->send->getNotificationData(__DIR__, self::NOTIFICATION_FILETEST);
        $notificationDataJson = $this->send->encodeNotificationData($notificationData);
        $this->assertNotFalse($notificationDataJson);
    }

    public function testWebPushInstance(): void
    {
        $auth = Send::AUTHENTIFICATOR_PROFILE;
        $webPush = new WebPush($auth);
        $this->assertInstanceOf(WebPush::class, $webPush);
    }

    public function testGoodOption(): void
    {
        $optionsWaited = ['TTL' => Send::TTL_NUMBER];
        $dataReaded = $this->send->getOptions();
        $this->assertEquals($optionsWaited, $dataReaded);
    }

    public function testSendNotificationWithBadJson(): void
    {
        $this->expectException(BadDataClassException::class);
        $this->expectExceptionMessage("Error encoding notification data to JSON");

        $subscriptionData = $this->send->getSubscriptionData(__DIR__, self::SUBSCRIPTION_FILETEST);
        $notificationDataJson = false;
        $this->send->sendOneNotification($subscriptionData, $notificationDataJson, []);
    }
}
