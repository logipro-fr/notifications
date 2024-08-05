<?php

namespace Notifications\Tests\Application\Service;

use Notifications\Application\Service\Unsubscription\Unsubscription;
use Notifications\Application\Service\Unsubscription\UnsubscriptionRequest;
use Notifications\Application\Service\Unsubscription\UnsubscriptionResponse;
use Notifications\Domain\Model\Publisher\Publisher;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\ExpirationTime;
use Notifications\Domain\Model\Subscriber\Keys;
use Notifications\Domain\Model\Subscriber\Status;
use Notifications\Domain\Model\Subscriber\Subscriber;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryInMemory;
use PHPUnit\Framework\TestCase;

class UnsubscriptionTest extends TestCase
{
    private $repository;
    private $unsubscription;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(SubscriberRepositoryInterface::class);
        $this->unsubscription = new Unsubscription($this->repository);
    }

    public function testExecute(): void
    {
        $endpoint = new Endpoint("test");
        $expirationTime = new ExpirationTime();
        $keys = new Keys("8veJjf8tjO1kbYlX3zOoRw", "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL");
        $publisher = new Publisher("www.nextsign.fr");
        
        $subscriber = new Subscriber($endpoint, $keys, $expirationTime, $publisher);

        $this->repository->method('findById')
            ->with($this->equalTo($endpoint))
            ->willReturn($subscriber);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($subscriber));

        $request = new UnsubscriptionRequest($endpoint, $expirationTime, $keys->getAuthKey(), $keys->getEncryptKey());

        $this->unsubscription->execute($request);
        
        $response = $this->unsubscription->getResponse();
        $this->assertInstanceOf(UnsubscriptionResponse::class, $response);
        $this->assertEquals('success', $response->getStatus());
    }
}
