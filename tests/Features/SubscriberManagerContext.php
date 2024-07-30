<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Notifications\Application\Service\Subscription;
use Notifications\Application\Service\SubscriptionRequest;
use Notifications\Domain\Model\Publisher\Publisher;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\ExpirationTime;
use Notifications\Domain\Model\Subscriber\Keys;
use Notifications\Domain\Model\Subscriber\Subscriber;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryInMemory;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpKernel\KernelInterface;

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

    private Endpoint $endpoint;
    private ExpirationTime $expirationTime;
    private Keys $keys;

    private string $response;
    private static KernelInterface $kernel;

     /**
     * @BeforeSuite
     */
    public static function prepare(BeforeSuiteScope $scope): void
    {
        self::$kernel = new \Notifications\Infrastructure\Shared\Symfony\Kernel('test', true);
        self::$kernel->boot();
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
         /** @var KernelBrowser */
         $client = self::$kernel->getContainer()->get('test.client');
         $client->request(
             "POST",
             "/api/v1/subscriber",
             [],
             [],
             ['CONTENT_TYPE' => 'application/json'],
             json_encode([
                "endpoint" => "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx",
                "expirationTime" => "",
                "keys" => [
                    "auth" => "8veJjf8tjO1kbYlX3zOoRw",
                    "p256dh" => "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL"
                ],
             ])
         );
         /** @var string */
         $response = $client->getResponse()->getContent();
         $this->response = $response;
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
        /** @var KernelBrowser */
        $client = self::$kernel->getContainer()->get('test.client');
        $client->request(
            "POST",
            "/api/v1/subscriber/authorization",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(["AuthorizedStatus" => false])
        );
        /** @var string */
        $response = $client->getResponse()->getContent();
        $this->response = $response;
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
