<?php

namespace Notifications\Application\Service\Unsubscription;

use Notifications\Domain\Subscriber\Subscriber;

class UnsubscriptionRequest
{
     /**
     * @param string $url
     * @param array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} $subscriberId
     */
    public function __construct(public readonly string $url, public readonly array $subscriberId)
    {
        //foreach ($subscriberId as $id) {
        //    if (!is_string($id)) {
        //        throw new \InvalidArgumentException('Each subscriberId must be a string');
        //    }
        //}
    }
}
