<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Notifications\Domain\Model\Publisher\Publisher;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\ExpirationTime;
use Notifications\Domain\Model\Subscriber\Keys;
use Notifications\Domain\Model\Subscriber\Subscriber;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;
use Notifications\Infrastructure\Api\V1\OptInController;
use Notifications\Infrastructure\Api\V1\PublisherController;
use Notifications\Infrastructure\Api\V1\WebPushNotificationController;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryInMemory;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Generator\Generator;
use Notifications\Application\Service\Subscription\Subscription;
use Notifications\Application\Service\Subscription\SubscriptionRequest;
use Notifications\Application\Service\Unsubscription\Unsubscription;
use Notifications\Application\Service\Unsubscription\UnsubscriptionRequest;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_encode;

/**
 * Defines application features from the specific context.
 */
class NotificationManagerContext implements Context
{
    private WebPushNotificationController $createNotification;
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

        /** @var MockObject $entityManager */
        $entityManager = (new Generator())->testDouble(
            EntityManagerInterface::class,
            true,
            true,
            callOriginalConstructor: false
        );
        /** @var EntityManagerInterface $entityManager */
        $this->createSubscriberController = new WebPushNotificationController(
            $this->subscribers,
            $entityManager
        );

        /** @var EntityManagerInterface $entityManager */
        $this->createNotification = new WebPushNotificationController();
    }

    /**
     * @When the user complete an action (for exemple a purchase)
     */
    public function theUserCompleteAnActionForExempleAPurchase()
    {
        throw new PendingException();
    }

    /**
     * @When a new article is published on the site
     */
    public function aNewArticleIsPublishedOnTheSite()
    {
        throw new PendingException();
    }

    /**
     * @Then a push notification entitled :arg1 with the body :arg2 and the icon :arg3 is sent to :arg4 and :arg5
     */
    public function aPushNotificationEntitledWithTheBodyAndTheIconIsSentToAnd($arg1, $arg2, $arg3, $arg4, $arg5)
    {
        throw new PendingException();
    }

    /**
     * @Then the notification contains the URL :arg1 for redirection
     */
    public function theNotificationContainsTheUrlForRedirection($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given the token of :arg1 has expired
     */
    public function theTokenOfHasExpired($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When a push notification is attempted to be sent to :arg1
     */
    public function aPushNotificationIsAttemptedToBeSentTo($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then the notification fails
     */
    public function theNotificationFails()
    {
        throw new PendingException();
    }

    /**
     * @Then the publisher tries to refresh the token of :arg1 or mark it as inactive
     */
    public function thePublisherTriesToRefreshTheTokenOfOrMarkItAsInactive($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given :arg1 received a notification less than an hour ago
     */
    public function receivedANotificationLessThanAnHourAgo($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When a new trigger event is recorded
     */
    public function aNewTriggerEventIsRecorded()
    {
        throw new PendingException();
    }

    /**
     * @Then the sending of the new notification to :arg1 is delayed to respect the frequency limitation
     */
    public function theSendingOfTheNewNotificationToIsDelayedToRespectTheFrequencyLimitation($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then the notification will be sent to :arg1 after a time period respecting his frequency limitation preference
     */
    public function theNotificationWillBeSentToAfterATimePeriodRespectingHisFrequencyLimitationPreference($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given :arg1 received a notification
     */
    public function receivedANotification($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given the payload contains some actions
     */
    public function thePayloadContainsSomeActions()
    {
        throw new PendingException();
    }

    /**
     * @When :arg1 clicks the :arg2 notification button
     */
    public function clicksTheNotificationButton($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then the page https:\/\/accidentprediction.fr\/accidents\/RN88 is opened
     */
    public function thePageHttpsAccidentpredictionFrAccidentsRnIsOpened()
    {
        throw new PendingException();
    }
}
