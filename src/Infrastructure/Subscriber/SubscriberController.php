<?php

namespace Notifications\Infrastructure\Subscriber;

use Minishlink\WebPush\Subscription;

class SubscriberController
{
    private $subscriptionsFile = '../subscriptions.json';

    public function subscribe()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if ($this->isValidSubscription($data)) {
                $subscription = new Subscription(
                    $data['endpoint'],
                    $data['keys']['p256dh'],
                    $data['keys']['auth']
                );

                $this->saveSubscription($subscription);

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Invalid subscription data']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
        }
    }

    private function isValidSubscription($data)
    {
        return isset($data['endpoint'], $data['keys']['p256dh'], $data['keys']['auth']);
    }

    private function saveSubscription($subscription)
    {
        $subscriptions = $this->loadSubscriptions();
        $subscriptions[] = $subscription;

        file_put_contents($this->subscriptionsFile, json_encode($subscriptions));
    }

    private function loadSubscriptions()
    {
        if (!file_exists($this->subscriptionsFile)) {
            return [];
        }

        $content = file_get_contents($this->subscriptionsFile);
        return json_decode($content, true);
    }
}
