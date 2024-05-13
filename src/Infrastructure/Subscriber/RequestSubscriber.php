<?php

namespace Notifications\Infrastructure\Subscriber;

class RequestSubscriber
{
    public function verifyHeader(callable $headerCallback): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subscriptionId = "LBhhw0OohO-Wl4Oi971UG";
            $clientUri = "/push/" . $subscriptionId;

            $headerCallback('Location: https://push.example.net' . $clientUri);
            $headerCallback('Link: <' . $clientUri . '>; rel="urn:ietf:params:push"');
            $headerCallback('</subscription-set/' . $subscriptionId . '>; rel="urn:ietf:params:push:set"');

            http_response_code(201);
        } else {
            $headerCallback('HTTP/1.1 405 Method Not Allowed');

            http_response_code(405);
        }
    }

    public function requestSub(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return "Subscription created successfully";
        } else {
            return "Method Not Allowed";
        }
    }
}
