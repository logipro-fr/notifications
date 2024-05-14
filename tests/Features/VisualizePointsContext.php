<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Notifications\Application\Service\Subscription\Subscription;
use Notifications\Application\Service\Subscription\SubscriptionRequest;
use Notifications\Domain\Publisher;
use Notifications\Domain\Subscriber;
use Notifications\Infrastructure\VapidGenerator;

/**
 * Defines application features from the specific context.
 */
class VisualizePointsContext implements Context
{
    private ?Publisher $websiteNotificationPublisher;
    private Subscriber $navigatorUserThatWantToSubscribe;
    private const URL_NOTIFICATION_PUBLISHER = "nextsign.fr";

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
        $this->websiteNotificationPublisher = new Publisher(self::URL_NOTIFICATION_PUBLISHER, $generator);
        $this->navigatorUserThatWantToSubscribe = new Subscriber();
    }

    /**
     * @When the user accepts to subscribe
     */
    public function theUserAcceptsToSubscribe(): void
    {
        $request = new SubscriptionRequest(self::URL_NOTIFICATION_PUBLISHER);

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
        $this->websiteNotificationPublisher = new Publisher(self::URL_NOTIFICATION_PUBLISHER, $generator);
        $this->websiteNotificationPublisher->subscribe($this->navigatorUserThatWantToSubscribe);
    }

    /**
     * @Then the navigator has a token that allows to recogize it
     */
    public function theNavigatorHasATokenThatAllowsToRecogizeIt(): void
    {
        throw new PendingException();
    }

    /**
     * @When the user refuse to subscribe
     */
    public function theUserRefuseToSubscribe(): void
    {
        throw new PendingException();
    }

    /**
     * @Then the navigator on the device don't become a new subscriber of the publisher
     */
    public function theNavigatorOnTheDeviceDontBecomeANewSubscriberOfThePublisher(): void
    {
        throw new PendingException();
    }

    /**
     * @Then the navigator hasn't a token that allows to recogize it
     */
    public function theNavigatorHasntATokenThatAllowsToRecogizeIt(): void
    {
        throw new PendingException();
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
