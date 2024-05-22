<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Notifications\Application\Service\Subscription\Subscription;
use Notifications\Application\Service\Subscription\SubscriptionRequest;
use Notifications\Domain\Entity\Notification\NotificationAddress;
use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Infrastructure\Keys\VapidGenerator;

/**
 * Defines application features from the specific context.
 */
class VisualizePointsContext implements Context
{
    private ?Publisher $websiteNotificationPublisher;
    private Subscriber $navigatorUserThatWantToSubscribe;
    private NotificationAddress $notificationAddress;
    private const URL_PUBLISHER = "nextsign.fr";
    /** @var array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} */
    private const SUB_ID = [
        "endpoint" => "nextsign.fr",
        "expirationTime" => null,
        "keys" => [
            "auth" => "",
            "p256dh" => ""
        ]
    ];

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->websiteNotificationPublisher = null;
    }

        /**
     * @Given a website notification publisher propose a user to subscribe to receive notification
     */
    public function aWebsiteNotificationPublisherProposeAUserToSubscribeToReceiveNotification(): void
    {
        $generator = new VapidGenerator();
        $this->notificationAddress = new NotificationAddress(self::SUB_ID);
        $userAdress = $this->notificationAddress->getAddress();
        $this->websiteNotificationPublisher = new Publisher(self::URL_PUBLISHER, $generator, $userAdress);
        $this->navigatorUserThatWantToSubscribe = new Subscriber();
    }

    /**
     * @When the user accepts to subscribe
     */
    public function theUserAcceptsToSubscribe(): void
    {
        $request = new SubscriptionRequest(self::SUB_ID);

        $generator = new VapidGenerator();
        $service = new Subscription($generator);
        $service->execute($request);
    }

    /**
     * @Then the navigator on the device become a new subscriber of the publisher
     */
    public function theNavigatorOnTheDeviceBecomeANewSubscriberOfThePublisher(): void
    {
        $generator = new VapidGenerator();
        $this->notificationAddress = new NotificationAddress(self::SUB_ID);
        $userAdress = $this->notificationAddress->getAddress();
        $this->websiteNotificationPublisher = new Publisher(self::URL_PUBLISHER, $generator, $userAdress);
        $this->websiteNotificationPublisher->subscribe($this->navigatorUserThatWantToSubscribe);
    }

    /**
     * @Then the navigator has a token that allows to recogize it
     */
    public function theNavigatorHasATokenThatAllowsToRecogizeIt(): void
    {
        $generator = new VapidGenerator();
        $generator->generateACoupleOfKey();
    }

    /**
     * @When the user refuse to subscribe
     */
    public function theUserRefuseToSubscribe(): void
    {
        throw new PendingException();
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
        $request = new SubscriptionRequest(self::SUB_ID);

        $generator = new VapidGenerator();
        $service = new Subscription($generator);
        $service->execute($request);
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
