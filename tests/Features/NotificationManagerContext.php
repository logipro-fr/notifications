<?php

namespace Features;

use Behat\Behat\Context\Context;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Defines application features from the specific context.
 */
class NotificationManagerContext implements Context
{
    private Client $httpClient;
    /**
     * @var array{
     *     notification: array{
     *         title: string,
     *         description: string,
     *         image: string,
     *         url: string,
     *     }
     * }
     */
    private array $lastNotificationPayload = [
        'notification' => [
            'title' => '',
            'description' => '',
            'image' => '',
            'url' => '',
        ]
    ];
    private bool $notificationFailed = false;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * @When a new article is published on the site
     */
    public function aNewArticleIsPublishedOnTheSite(): void
    {
        $this->lastNotificationPayload = [
            'notification' => [
                'title' => 'New Article Published',
                'description' => 'A new article has been published on the site.',
                'image' => '',
                'url' => 'https://example.com'
            ]
        ];
    }

    /**
     * @Then a push notification entitled :arg1 with the body :arg2 and the icon :arg3 is sent to :arg4 and :arg5
     */
    public function aPushNotificationEntitledWithTheBodyAndTheIconIsSentToAnd(
        string $arg1,
        string $arg2,
        string $arg3,
        string $arg4,
    ): void {
        $response = $this->httpClient->request('GET', 'http://172.17.0.1:11480/api/v1/subscriber/notifications');

        $responseBody = $response->getBody()->getContents();
        $notifications = json_decode($responseBody, true);

        if (!is_array($notifications)) {
            throw new \Exception('Invalid response format');
        }

        $notification = array_filter($notifications, function ($n) use ($arg1, $arg2, $arg3, $arg4) {
            return is_array($n) &&
                isset($n['title'], $n['body'], $n['icon'], $n['url']) &&
                $n['title'] === $arg1 &&
                $n['body'] === $arg2 &&
                $n['icon'] === $arg3 &&
                $n['icon'] === $arg4;
        });

        if (empty($notification)) {
            throw new \Exception('Notification not found or does not match the expected values.');
        }
    }

    /**
     * @Then the notification contains the URL :arg1 for redirection
     */
    public function theNotificationContainsTheUrlForRedirection(string $arg1): void
    {
        if (
            !isset($this->lastNotificationPayload['notification']['url']) ||
            $this->lastNotificationPayload['notification']['url'] !== $arg1
        ) {
            throw new \Exception('Notification URL does not match the expected value.');
        }
    }

    /**
     * @Given the token of :arg1 has expired
     */
    public function theTokenOfHasExpired(string $arg1): void
    {
        $this->httpClient->request('POST', 'http://172.17.0.1:11480/api/v1/subscriber/token-expired', [
            'json' => ['token' => $arg1]
        ]);
    }

    /**
     * @When a push notification is attempted to be sent to :arg1
     */
    public function aPushNotificationIsAttemptedToBeSentTo(string $arg1): void
    {
        try {
            $this->httpClient->request('POST', 'http://172.17.0.1:11480/api/v1/subscriber/send', [
                'json' => [
                    'endpoint' => $arg1,
                    'keys' => ['auth' => '', 'p256dh' => ''],
                    'notification' => ['title' => 'Test', 'description' => 'Test body']
                ]
            ]);
            $this->notificationFailed = false;
        } catch (RequestException $e) {
            $this->notificationFailed = true;
        }
    }

    /**
     * @Then the notification fails
     */
    public function theNotificationFails(): void
    {
        if (!$this->notificationFailed) {
            throw new \Exception('Expected the notification to fail, but it succeeded.');
        }
    }

    /**
     * @Then the publisher tries to refresh the token of :arg1 or mark it as inactive
     */
    public function thePublisherTriesToRefreshTheTokenOfOrMarkItAsInactive(string $arg1): void
    {
        $this->httpClient->request('POST', 'http://172.17.0.1:11480/api/v1/subscriber/refresh-token', [
            'json' => ['token' => $arg1]
        ]);
    }

    /**
     * @Given :arg1 received a notification less than an hour ago
     */
    public function receivedANotificationLessThanAnHourAgo(string $arg1): void
    {
        $this->httpClient->request('POST', 'http://172.17.0.1:11480/api/v1/subscriber/notification-received', [
            'json' => ['subscriber' => $arg1, 'time' => (new \DateTime('-30 minutes'))->format(DATE_ATOM)]
        ]);
    }

    /**
     * @When a new trigger event is recorded
     */
    public function aNewTriggerEventIsRecorded(): void
    {
        $this->httpClient->request('POST', 'http://172.17.0.1:11480/api/v1/subscriber/trigger-event');
    }

    /**
     * @Given :arg1 received a notification
     */
    public function receivedANotification(string $arg1): void
    {
        $this->httpClient->request('POST', 'http://172.17.0.1:11480/api/v1/subscriber/received-notification', [
            'json' => ['subscriber' => $arg1]
        ]);
    }

    /**
     * @Given the payload contains action
     */
    public function thePayloadContainsAction(): void
    {
        $this->lastNotificationPayload = [
            'notification' => [
                'title' => '',
                'description' => '',
                'image' => '',
                'url' => ''
            ]
        ];
    }

    /**
     * @When :arg1 clicks the :arg2 notification button
     */
    public function clicksTheNotificationButton(string $arg1, string $arg2): void
    {
        $this->httpClient->request('POST', 'http://172.17.0.1:11480/api/v1/subscriber/click-notification-button', [
            'json' => ['subscriber' => $arg1, 'button' => $arg2]
        ]);
    }

    /**
     * @Then the page https:\/\/accidentprediction.fr\/accidents\/RN88 is opened
     */
    public function thePageHttpsAccidentpredictionFrAccidentsRnIsOpened(): void
    {
        if ($this->lastNotificationPayload['notification']['url'] !== 'https://accidentprediction.fr/accidents/RN88') {
            throw new \Exception('The page URL does not match the expected URL.');
        }
    }
}
