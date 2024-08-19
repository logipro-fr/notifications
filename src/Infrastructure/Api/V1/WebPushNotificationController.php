<?php

namespace Notifications\Infrastructure\Api\V1;

use Minishlink\WebPush\Subscription as WebPushSubscription;
use Minishlink\WebPush\WebPush;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebPushNotificationController
{
    private WebPush $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject' => 'https://github.com/logipro-fr/notifications/', 
                'publicKey' => 'BMBlr6YznhYMX3NgcWIDRxZXs0sh7tCv7_YCsWcww0ZCv9WGg-tRCXfMEHTiBPCksSqeve1twlbmVAZFv7GSuj0', 
                'privateKey' => 'vplfkITvu0cwHqzK9Kj-DYStbCH_9AhGx9LqMyaeI6w', 
            ],
        ];
        $this->webPush = new WebPush($auth);
    }

    #[Route('/api/v1/subscriber/send', name: 'sendNotification', methods: ['POST'])]
    public function sendNotification(Request $request): JsonResponse
    {
        $response = json_decode($request->getContent(), true);

        $webpushSubscription = new WebPushSubscription($response['endpoint'], $response['keys']['auth'], $response['keys']['p256dh']);
        $this->webPush->sendOneNotification(
            $webpushSubscription,
            '{"message":"Hello! 👋"}',
        );
        return new JsonResponse(
            [
                'success' => true,
                'ErrorCode' => "",
                'data' => [
                    '{"message":"Hello! 👋"}'
                ],
                'message' => "",
            ],
            201
        );
    }
}
