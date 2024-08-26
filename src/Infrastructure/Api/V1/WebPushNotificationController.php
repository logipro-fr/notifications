<?php

namespace Notifications\Infrastructure\Api\V1;

use Minishlink\WebPush\Subscription as WebPushSubscription;
use Minishlink\WebPush\WebPush;
use Notifications\Domain\Model\Notification\Action;
use Notifications\Domain\Model\Notification\Description;
use Notifications\Domain\Model\Notification\Icon;
use Notifications\Domain\Model\Notification\Notification;
use Notifications\Domain\Model\Notification\Title;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use function Safe\json_decode;

class WebPushNotificationController
{
    private WebPush $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject' =>
                    'https://github.com/logipro-fr/notifications/',
                'publicKey' =>
                    'BMBlr6YznhYMX3NgcWIDRxZXs0sh7tCv7_YCsWcww0ZCv9WGg-tRCXfMEHTiBPCksSqeve1twlbmVAZFv7GSuj0',
                'privateKey' =>
                    'vplfkITvu0cwHqzK9Kj-DYStbCH_9AhGx9LqMyaeI6w',
            ],
        ];
        $this->webPush = new WebPush($auth);
    }

    #[Route('/api/v1/subscriber/send', name: 'sendNotification', methods: ['POST'])]
    public function sendNotification(Request $request): JsonResponse
    {
        try {
            $response = json_decode($request->getContent(), true) ?? [];
            if (
                !is_string($response['endpoint']) ||
                !is_string($response['keys']['auth']) ||
                !is_string($response['keys']['p256dh']) ||
                empty($response['endpoint']) ||
                empty($response['keys']['auth']) ||
                empty($response['keys']['p256dh']) ||
                !is_array($response)
            ) {
                throw new \InvalidArgumentException("Invalid subscription data");
            }

            $webpushSubscription = new WebPushSubscription(
                $response['endpoint'],
                $response['keys']['auth'],
                $response['keys']['p256dh']
            );
            $payload = $this->prepareNotificationObject($response['notification']);

            $this->webPush->sendOneNotification($webpushSubscription, $payload);

            return new JsonResponse([
                'success' => true,
                'ErrorCode' => "",
                'data' => [$payload],
                'message' => ""], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'ErrorCode' => $e->getMessage(),
                'message' => "An error occurred"], 400);
        }
    }

    /** @param array{title: string, description: string, image: ?string, url: ?string} $data
     * @return string
    */
    private function prepareNotificationObject(array $data): string
    {
        if (empty($data['title']) || empty($data['description'])) {
            throw new \InvalidArgumentException("Invalid notification data");
        }
        $title = $data['title'];
        $body = $data['description'];
        $icon = $data['image'] ?? '';
        $url = $data['url'] ?? '';

        $notification = new Notification(
            new Title($title),
            new Description($body),
            new Action($url),
            new Icon($icon)
        );

        $payload = [
            'title' => $notification->getTitle()->__toString(),
            'body' => $notification->getDescription()->__toString(),
        ];

        if (!empty($notification->getIcon()->getIcon())) {
            $payload['icon'] = $notification->getIcon()->getIcon();
        }
        if (!empty($notification->getAction()->__toString())) {
            $payload['url'] = $notification->getAction()->__toString();
        }

        $encodedPayload = json_encode($payload);
        return $encodedPayload;
    }
}
