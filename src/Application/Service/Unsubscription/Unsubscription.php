<?php

namespace Notifications\Application\Service\Unsubscription;

use Notifications\Domain\KeyGeneratorStrategy;

class Unsubscription
{
    private UnsubscriptionResponse $response;

    public function execute(UnsubscriptionRequest $request): void
    {
        $subscriberId = $request->subscriberId;
        $this->response = new UnsubscriptionResponse("User with ID unsubscribed successfully.");
    }

    public function getResponse(): UnsubscriptionResponse
    {
        return $this->response;
    }
}
