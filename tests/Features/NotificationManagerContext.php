<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

use function Safe\json_encode;

/**
 * Defines application features from the specific context.
 */
class NotificationManagerContext implements Context
{
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
