<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Minishlink\WebPush\VAPID;
use Notifications\Application\Service\Subscription\Subscription;
use Notifications\Application\Service\Subscription\SubscriptionRequest;
use Notifications\Domain\KeyGeneratorStrategy;
use Notifications\Domain\Publisher;
use Notifications\Domain\Subscriber;
use Notifications\Infrastructure\VapidGenerator;

/**
 * Defines application features from the specific context.
 */
class RegistrationWithConsentContext implements Context
{
    private ?Publisher $websiteNotificationPublisher;
    private Subscriber $navigatorUserThatWantToSubscribeWithFirefox;
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
    public function aWebsiteNotificationPublisherProposeAUserToSubscribeToReceiveNotification()
    {
        $generator = new VapidGenerator();
        $this->websiteNotificationPublisher = new Publisher(self::URL_NOTIFICATION_PUBLISHER, $generator);
        $this->navigatorUserThatWantToSubscribeWithFirefox = new Subscriber();
    }

    /**
     * @When the user accepts to subscribe
     */
    public function theUserAcceptsToSubscribe()
    {
        $request = new SubscriptionRequest(self::URL_NOTIFICATION_PUBLISHER);

        $generator = new VapidGenerator();
        $service = new Subscription($generator);
        //$service = new Subscription($generator, $jeSuisLeSupportDesPubloishers);
        $service->execute($request);
    }

    /**
     * @Then the navigator on the device become a new subscriber of the publisher
     */
    public function theNavigatorOnTheDeviceBecomeANewSubscriberOfThePublisher()
    {

        throw new PendingException();
    }

    /**
     * @Then the navigator has a token that allows to recogize it
     */
    public function theNavigatorHasATokenThatAllowsToRecogizeIt()
    {
        throw new PendingException();
    }
}
