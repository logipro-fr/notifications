<?php

namespace Notifications\public;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Notifications\Application\Service\FileManager\ObtainData;
use Notifications\Domain\Exceptions\BadDataClassException;

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
        $subscriptionData = $this->getSubscriptionData(__DIR__, "subscription.json");
        $notificationData = $this->getNotificationData(__DIR__, "notification.json");
        ;
        $notificationDataJson = $this->encodeNotificationData($notificationData);

        $options = $this->getOptions();

        $report = $this->sendOneNotification($subscriptionData, $notificationDataJson, $options);
        print_r($report);
    }

    /**
     * @param string $dir
     * @param string $filename
     * @return array<mixed>
     */
    public function getSubscriptionData(string $dir, string $filename): array
    {
        return (new ObtainData())->readJSON($dir, $filename);
    }

    public function getNotificationData(string $dir, string $filename): mixed
    {
        return (new ObtainData())->readJSON($dir, $filename);
    }

    public function encodeNotificationData(mixed $data): string|false
    {
        $notificationDataJson = json_encode($data);
        return $notificationDataJson;
    }

    /**
     * @param array<mixed> $subscriptionData
     * @param string|false $notificationDataJson
     * @param int[] $options
     * @return mixed
     */
    public function sendOneNotification(array $subscriptionData, string|false $notificationDataJson, $options): mixed
    {
        if ($notificationDataJson === false) {
            throw new BadDataClassException("Error encoding notification data to JSON");
        }
        $auth = self::AUTHENTIFICATOR_PROFILE;
        $webPush = new WebPush($auth);
        return $webPush->sendOneNotification(
            Subscription::create($subscriptionData),
            $notificationDataJson,
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
