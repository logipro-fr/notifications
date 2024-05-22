<?php

namespace Notifications\public;

use Minishlink\WebPush\MessageSentReport;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Notifications\Domain\Exceptions\BadDataClassException;
use Notifications\Infrastructure\FileManager\ObtainData;

class Send
{
    public const TTL_NUMBER = 5000;

    public const PUBLIC_KEY = 'BO71HBUxeIRbjm7m8Ed3mC_11XRv2OIpzEykqHLCIOc2Ol1H_R9zzIwCt69wkwPbGqbqbytdvikVAa0QKFqeyiM';
    public const PRIVATE_KEY = 'GSLEPUwC4hx9G4eY_w_7nx3C3tQSSE-UrQyUOMWCf9s';

    public const AUTHENTIFICATOR_PROFILE = [
        'VAPID' => [
            'subject' => 'mailto:me@website.com',
            'publicKey' => Send::PUBLIC_KEY,
            'privateKey' => Send::PRIVATE_KEY,
        ],
    ];

    /**
     * @throws BadDataClassException
     */
    public function sendNotification(): void
    {
        $userAddress = $this->getSubscriptionData(__DIR__, "subscription.json");
        $notificationData = $this->getNotificationData(__DIR__, "notification.json");
        $notificationContent = $this->encodeNotificationData($notificationData);

        $options = $this->getOptions();

        $report = $this->sendOneNotification($userAddress, $notificationContent, $options);
        print_r($report);
    }

    /**
     * @param string $dir
     * @param string $filename
     * @return array<string>
     */
    public function getSubscriptionData(string $dir, string $filename): array
    {
        return (new ObtainData())->readJSON($dir, $filename);
    }

     /**
     * @param string $dir
     * @param string $filename
     * @return array<string>
     */
    public function getNotificationData(string $dir, string $filename): array
    {
        return (new ObtainData())->readJSON($dir, $filename);
    }

     /**
     * @param array<string> $data
     * @return string|false
     */
    public function encodeNotificationData(array $data): string|false
    {
        $notificationContent = json_encode($data);
        return $notificationContent;
    }

    /**
     * @param array<string> $userAddress
     * @param string|false $notificationContent
     * @param int[] $options
     * @return MessageSentReport
     */
    public function sendOneNotification($userAddress, $notificationContent, $options): MessageSentReport
    {
        if ($notificationContent === false) {
            throw new BadDataClassException("Error encoding notification data to JSON");
        }
        $auth = self::AUTHENTIFICATOR_PROFILE;
        $webPush = new WebPush($auth);
        return $webPush->sendOneNotification(
            Subscription::create($userAddress),
            $notificationContent,
            $options
        );
    }

    /**
     * @return int[]
     */
    public function getOptions()
    {
        $options = ['TTL' => self::TTL_NUMBER];
        return $options;
    }
}
