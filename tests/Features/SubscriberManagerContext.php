<?php

namespace Features;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Generator\Generator;
use Notifications\Application\Service\Subscription\Subscription;
use Notifications\Application\Service\Subscription\SubscriptionRequest;
use Notifications\Application\Service\Unsubscription\Unsubscription;
use Notifications\Application\Service\Unsubscription\UnsubscriptionRequest;
use Notifications\Domain\Model\Publisher\Publisher;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\ExpirationTime;
use Notifications\Domain\Model\Subscriber\Keys;
use Notifications\Domain\Model\Subscriber\Subscriber;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;
use Notifications\Infrastructure\Api\V1\OptInController;
use Notifications\Infrastructure\Api\V1\PublisherController;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryInMemory;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_encode;

/**
 * Defines application features from the specific context.
 */
class SubscriberManagerContext implements Context
{
    private const URL_PUBLISHER = "nextsign.fr";

    private Publisher $website;
    private Subscriber $subscriber;
    private SubscriberRepositoryInMemory $repository;
    private SubscriberRepositoryInterface $subscribers;

    private PublisherController $createSubscriberController;
    private OptInController $createSubscriberOPT;

    private Endpoint $endpoint;
    private ExpirationTime $expirationTime;
    private Keys $keys;

    public function __construct()
    {
        $this->subscribers = new SubscriberRepositoryInMemory();
        $entityManager = (new Generator())->testDouble(
            EntityManagerInterface::class,
            true,
            true,
            callOriginalConstructor: false
        );
        /** @var EntityManagerInterface $entityManager */
        $this->createSubscriberController = new PublisherController(
            $this->subscribers,
            $entityManager
        );

        /** @var EntityManagerInterface $entityManager */
        $this->createSubscriberOPT = new OptInController();
    }

    /**
     * @Given a website notification publisher propose a user to subscribe to receive notification
     */
    public function aWebsiteNotificationPublisherProposeAUserToSubscribeToReceiveNotification(): void
    {
        $this->website = new Publisher(self::URL_PUBLISHER);
    }

    /**
     * @When the user accepts to subscribe
     */
    public function theUserAcceptsToSubscribe(): void
    {
        $this->repository = new SubscriberRepositoryInMemory();
        $subscription = new Subscription($this->repository);
        $this->endpoint = new Endpoint(self::URL_PUBLISHER);
        $this->keys = new Keys(
            "H9M9HgHX4a3xmcChKQNWFA",
            "BNBaksmindsZK9u_mghq-Omb1_9bN-hJVP8KjLWB6mlHPf_R3JLmyd-0LwYBGErAjItB2Pex6bAKYFFR_gDdYpo"
        );
        $this->expirationTime = new ExpirationTime();
        $request = new SubscriptionRequest(
            $this->endpoint,
            $this->expirationTime,
            $this->keys->getAuthKey(),
            $this->keys->getEncryptKey()
        );
        $subscription->execute($request);
    }

    /**
     * @Then the navigator on the device become a new subscriber of the publisher
     */
    public function theNavigatorOnTheDeviceBecomeANewSubscriberOfThePublisher(): void
    {
        $this->subscriber = new Subscriber($this->endpoint, $this->keys, $this->expirationTime, $this->website);
        $data = json_encode([
            "endpoint" => "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx",
            "expirationTime" => "",
            "keys" => [
                "auth" => "8veJjf8tjO1kbYlX3zOoRw",
                "p256dh" => "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL"
            ],
        ]);

        $request = Request::create(
            "/api/v1/subscriber/manager",
            "POST",
            content: $data
        );

        $this->createSubscriberController->execute($request);
    }

    /**
     * @Then the navigator has a token that allows to recogize it
     */
    public function theNavigatorHasATokenThatAllowsToRecogizeIt(): void
    {
        $this->subscriber->getKeys();
    }

    /**
     * @When the user refuse to subscribe
     */
    public function theUserRefuseToSubscribe(): void
    {
        $request = Request::create(
            "/api/v1/subscriber/authorization",
            "POST",
            content: json_encode(["AuthorizedStatus" => false])
        );
        $this->createSubscriberOPT->execute($request);
    }

    /**
     * @Then nothing happens
     */
    public function nothingHappens(): void
    {
    }

    /**
     * @When the user want to unsubscribe
     */
    public function theUserWantToUnsubscribe(): void
    {
        $this->repository = new SubscriberRepositoryInMemory();
        $unsubscription = new Unsubscription($this->repository);
        $this->endpoint = new Endpoint(self::URL_PUBLISHER);
        $this->keys = new Keys(
            "H9M9HgHX4a3xmcChKQNWFA",
            "BNBaksmindsZK9u_mghq-Omb1_9bN-hJVP8KjLWB6mlHPf_R3JLmyd-0LwYBGErAjItB2Pex6bAKYFFR_gDdYpo"
        );
        $this->expirationTime = new ExpirationTime();
        $request = new UnsubscriptionRequest(
            $this->endpoint,
            $this->expirationTime,
            $this->keys
        );
        $unsubscription->execute($request);
    }

    /**
     * @Then the navigator unsubscribed from publisher
     */
    public function theNavigatorUnsubscribedFromPublisher(): void
    {
        $this->subscriber = new Subscriber($this->endpoint, $this->keys, $this->expirationTime, $this->website);
        $data = json_encode([
            "endpoint" => "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx",
            "expirationTime" => "",
            "keys" => [
                "auth" => "8veJjf8tjO1kbYlX3zOoRw",
                "p256dh" => "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL"
            ],
        ]);

        $request = Request::create(
            "/api/v1/subscriber/manager",
            "DELETE",
            content: $data
        );

        $this->createSubscriberController->execute($request);
    }

    /**
     * @Then the navigator deleted the token that allows to recogize it
     */
    public function theNavigatorDeletedTheTokenThatAllowsToRecogizeIt(): void
    {
        if ($this->subscriber !== null) {
            $this->subscriber->getKeys();
        }
    }
}
