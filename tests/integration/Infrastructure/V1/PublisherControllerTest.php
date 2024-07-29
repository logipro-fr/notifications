<?php

namespace Notifications\Tests\Integration\Infrastructure\Lib;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\json_encode;

class PublisherControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private SubscriberRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["subscribers"]);

        $this->client = static::createClient(["debug" => false]);

        /** @var SubscriberRepositoryInterface $repo */
        $repo = $this->client->getContainer()->get("subscribers.repository");
        $this->repository = $repo;
    }

    public function testControllerRouting(): void
    {
        $content = json_encode([
            "endpoint" => "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx",
            "expirationTime" => "",
            "keys" => [
                "auth" => "8veJjf8tjO1kbYlX3zOoRw",
                "p256dh" => "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL"
            ],
        ]);

        $this->client->request(
            "POST",
            "/api/v1/subscriber/register",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );

        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();

        if ($responseContent === false) {
            $this->fail("Failed to get response content.");
        }

        /** @var array{success: bool, ErrorCode: string, data: array{endpoint: string, expirationTime: string, keys: array{auth: string, p256dh: string}}|null, message: string} $array */
        $array = json_decode($responseContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail("Failed to decode JSON: " . json_last_error_msg());
        }

        $endpoint = $array['data']['endpoint'];
        $this->repository->findById(new Endpoint($endpoint));

        $subscriber = $this->repository->findById(new Endpoint($endpoint));
        $this->assertNotNull($subscriber, "Subscriber with endpoint '{$endpoint}' not found.");
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseCode);
        $this->assertStringContainsString('"ErrorCode":', $responseContent);
        $this->assertStringContainsString('"endpoint":', $responseContent);
    }
}
