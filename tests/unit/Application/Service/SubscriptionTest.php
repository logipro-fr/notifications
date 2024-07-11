<?php

namespace Notifications\Tests\Application\Service;

use Notifications\Application\Service\Subscription;
use Notifications\Application\Service\SubscriptionRequest;
use Notifications\Application\Service\SubscriptionResponse;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Entity\Subscriber\Status;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryInMemory;

use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    private SubscriptionRequest $request;
    private SubscriberRepositoryInMemory $repository;

    public function setUp(): void
    {
        $endpoint = new Endpoint("test");
        $expirationTime = new ExpirationTime();
        $keys = new Keys("8veJjf8tjO1kbYlX3zOoRw", "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL");

        $this->request = new SubscriptionRequest(
            $endpoint,
            $expirationTime,
            $keys->getAuthKey(),
            $keys->getEncryptKey()
        );

        $this->repository = new SubscriberRepositoryInMemory();
    }

    public function testExecute(): void
    {
        $service = new Subscription($this->repository);

        if (empty($this->request->endpoint)) {
            $this->fail("Subscription request contains an invalid endpoint.");
        }

        $service->execute($this->request);
        $response = $service->getResponse();
        $postFromRepo = $this->repository->findById(new Endpoint($this->request->endpoint));

        $this->assertInstanceOf(SubscriptionResponse::class, $response);
        $this->assertEquals(Status::SUBSCRIBED, $postFromRepo->getStatus());
    }
}
