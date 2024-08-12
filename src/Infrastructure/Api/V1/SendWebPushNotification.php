<?php

namespace Notifications\Infrastructure\Api\V1;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Symfony\Component\Routing\Annotation\Route;
use function Safe\json_decode;

class SendWebPushNotification{
    #[Route('/api/v1/subscriber/send', name: 'uniqueNotification', methods: ['POST'])]
    public function sendToOneSubscriber(string $subscription, string $notification):void
    {
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:me@website.com', // can be a mailto: or your website address
                'publicKey' => 'BLWKe9pIQa2mHgqh2eI4b_a-XgZFbFyvLqRA3-eUtKehdXtRGuqjIVKfkBmhm8ZtcMF_q0oEPKBVjZyqF9KzTdg', // (recommended) uncompressed public key P-256 encoded in Base64-URL
                'privateKey' => 'M0GqiHBWLHB12TwSnoVVTxFqo621Z_J1hHSNr7KbcGs', // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL 
            ],
        ];

        $webPush = new WebPush($auth);

        $report = $webPush->sendOneNotification(Subscription::create(json_decode($subscription, true))
                , '{"title":"Hi from php" , "body":"php is amazing!" , "url":"./?message=123"}', ['TTL' => 5000]);

        print_r($report);
    }
}