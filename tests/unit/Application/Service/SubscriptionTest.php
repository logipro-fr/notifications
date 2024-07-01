<?php

namespace Notifications\Tests\Application\Service;

use Notifications\Application\Service\ApiInterface;
use Notifications\Application\Service\Subscription;
use Notifications\Application\Service\SubscriptionRequest;
use Notifications\Application\Service\SubscriptionResponse;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Entity\Subscriber\Status;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryInMemory;
use Notifications\Infrastructure\Provider\ProviderResponse;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    private SubscriptionRequest $request;
    private SubscriberRepositoryInMemory $repository;
    private Subscriber $sub;

    public function setUp(): void
    {
        $endpoint = new Endpoint("test");
        $expirationTime = new ExpirationTime();
        $keys = new Keys();
        $keys->generateACoupleOfKey();
        $this->request = new SubscriptionRequest(
            $endpoint,
            $expirationTime,
            $keys
        );
        $this->sub = new Subscriber($endpoint, $keys, $expirationTime);
        $this->repository = new SubscriberRepositoryInMemory();
    }

    public function testExecute(): void
    {
        $mockInterface = $this->createMock(ApiInterface::class);
        $mockInterface
            ->expects($this->once())
            ->method('subscriberApiRequest')
            ->willReturn(new ProviderResponse(new Endpoint("test")));
        $service = new Subscription($mockInterface, $this->repository, "test");
        $this->repository->add($this->sub);
        $service->execute($this->request);
        $response = $service->getResponse();
        $subscriber = $this->repository->findById(new Endpoint($response->endpoint));

        $this->assertInstanceOf(SubscriptionResponse::class, $response);
        $this->assertEquals("test", $response->endpoint);
        $this->assertEquals("test", $this->repository->findById(new Endpoint("test"))->getEndpoint());
        $this->assertEquals(Status::SUBSCRIBED, $subscriber->getStatus());
    }
}
