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
                    file_get_contents(__DIR__ . '/../../../../keys/public_key.txt'),
                'privateKey' =>
                    file_get_contents(__DIR__ . '/../../../../keys/private_key.txt'),
            ],
        ];
        $this->webPush = new WebPush($auth);
    }

    #[Route('/api/v1/subscriber/send', name: 'sendNotification', methods: ['POST'])]
    public function sendNotification(Request $request): JsonResponse
    {
        try {
            $response = json_decode($request->getContent(), true);
            if (!$this->isValidSubscriptionData($response)) {
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

        $jsonPayload = json_encode($payload);
        return $jsonPayload;
    }

    private function isValidSubscriptionData(array $response): bool
    {
        if (!is_string($response['endpoint']) || empty($response['endpoint'])) {
            return false;
        }
        if (!isset($response['keys']['auth'], $response['keys']['p256dh'])) {
            return false;
        }
        if (!is_string($response['keys']['auth']) || empty($response['keys']['auth'])) {
            return false;
        }
        if (!is_string($response['keys']['p256dh']) || empty($response['keys']['p256dh'])) {
            return false;
        }
        return true;
    }
}
