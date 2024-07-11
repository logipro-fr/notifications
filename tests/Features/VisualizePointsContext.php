<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Notifications\Application\Service\Subscription;
use Notifications\Domain\Entity\Publisher\Keys;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Infrastructure\Keys\VapidGenerator;

/**
 * Defines application features from the specific context.
 */
class VisualizePointsContext implements Context
{
    private const URL_PUBLISHER = "nextsign.fr";

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
        $endpoint = new Endpoint(self::URL_PUBLISHER);
        $generator = new Keys();
        $expirationTime = new ExpirationTime();
    }

    /**
     * @When the user accepts to subscribe
     */
    public function theUserAcceptsToSubscribe(): void
    {
    }

    /**
     * @Then the navigator on the device become a new subscriber of the publisher
     */
    public function theNavigatorOnTheDeviceBecomeANewSubscriberOfThePublisher(): void
    {
        $generator = new VapidGenerator();
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
