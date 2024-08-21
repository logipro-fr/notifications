<?php

namespace Notifications\Tests\Application\Service;

use Exception;
use Notifications\Application\Service\Unsubscription\Unsubscription;
use Notifications\Application\Service\Unsubscription\UnsubscriptionRequest;
use Notifications\Application\Service\Unsubscription\UnsubscriptionResponse;
use Notifications\Domain\Exceptions\SubscriberNotFoundException;
use Notifications\Domain\Model\Publisher\Publisher;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\ExpirationTime;
use Notifications\Domain\Model\Subscriber\Keys;
use Notifications\Domain\Model\Subscriber\Subscriber;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryInMemory;
use PHPUnit\Framework\TestCase;

class UnsubscriptionTest extends TestCase
{
    private UnsubscriptionRequest $request;
    private SubscriberRepositoryInMemory $repository;

    public function setUp(): void
    {
        $endpoint = new Endpoint(
            "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx"
        );
        $expirationTime = new ExpirationTime();
        $keys = new Keys(
            "8veJjf8tjO1kbYlX3zOoRw",
            "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL"
        );

        $publisher = new Publisher("www.nextsign.fr");

        $subscriber = new Subscriber($endpoint, $keys, $expirationTime, $publisher);
        $this->repository = new SubscriberRepositoryInMemory();
        $this->repository->add($subscriber);

        $this->request = new UnsubscriptionRequest(
            $endpoint->__toString(),
            $expirationTime,
            $keys
        );
    }

    public function testExecute(): void
    {
        $serviceUnsub = new Unsubscription($this->repository);
        if (empty($this->request->endpoint)) {
            $this->fail("Unsubscription request contains an invalid endpoint.");
        }
        $serviceUnsub->execute($this->request);
        $response = $serviceUnsub->getResponse();

        $this->assertInstanceOf(UnsubscriptionResponse::class, $response);
    }
}
