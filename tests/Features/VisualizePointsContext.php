<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Notifications\Application\Service\Subscription;
use Notifications\Application\Service\SubscriptionRequest;
use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Domain\Services\AuthorizationStatus;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryInMemory;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class VisualizePointsContext implements Context
{
    private const URL_PUBLISHER = "nextsign.fr";

    private Publisher $website;
    private Subscriber $subscriber;
    private SubscriberRepositoryInMemory $repository;
    private AuthorizationStatus $authorizationStatus;

    private Endpoint $endpoint;
    private ExpirationTime $expirationTime;
    private Keys $keys;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
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
        $this->authorizationStatus->isAuthorized();
    }

    /**
     * @Then the navigator on the device become a new subscriber of the publisher
     */
    public function theNavigatorOnTheDeviceBecomeANewSubscriberOfThePublisher(): void
    {
        $this->subscriber = new Subscriber($this->endpoint, $this->keys, $this->expirationTime, $this->website);
    }

    /**
     * @Then the navigator has a token that allows to recogize it
     */
    public function theNavigatorHasATokenThatAllowsToRecogizeIt(): void
    {
        $endpointInDatabase = $this->repository->findById($this->subscriber->getEndpoint());
        Assert::assertEquals($this->endpoint, $endpointInDatabase);
    }

    /**
     * @When the user refuse to subscribe
     */
    public function theUserRefuseToSubscribe(): void
    {
        $this->authorizationStatus->setAuthorization(false);
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
        throw new PendingException();
    }

    /**
     * @Then the navigator unsubscribed from publisher
     */
    public function theNavigatorUnsubscribedFromPublisher(): void
    {
        throw new PendingException();
    }

    /**
     * @Then the navigator deleted the token that allows to recogize it
     */
    public function theNavigatorDeletedTheTokenThatAllowsToRecogizeIt(): void
    {
        throw new PendingException();
    }

    /**
     * @When the user complete an action (for exemple a purchase)
     */
    public function theUserCompleteAnActionForExempleAPurchase(): void
    {
        throw new PendingException();
    }

    /**
     * @Then the navigator receives an invitation to subscribe for the publisher
     */
    public function theNavigatorReceivesAnInvitationToSubscribeForThePublisher(): void
    {
        throw new PendingException();
    }

    /**
     * @Then if the user accept, the navigator has a token that allows to recogize it
     */
    public function ifTheUserAcceptTheNavigatorHasATokenThatAllowsToRecogizeIt(): void
    {
        throw new PendingException();
    }
}
