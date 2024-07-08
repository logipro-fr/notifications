<?php

namespace Notifications\Tests\Integration\Infrastructure\Lib;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Entity\Subscriber\SubscriberRepositoryInterface;
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

        /** @var SubscriberRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("subscribers.repository");
        $this->repository = $autoInjectedRepo;

        $keys = new Keys();
        $keys->generateACoupleOfKey();
    }

    public function testControllerErrorResponse(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/subscriber/register",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "endpoint" => "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx",
                "expirationTime" => "",
                "keys" => "{
                    auth: 8veJjf8tjO1kbYlX3zOoRw,
                    p256dh: BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL
                }",

            ])
        );
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();

        $this->assertResponseIsUnprocessable();
        $this->assertEquals(422, $responseCode);
        $this->assertStringContainsString('"success":false', $responseContent);
    }
    public function testControllerRouting(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/subscriber/register",
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
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();

        /** @var array<mixed,array<mixed>> */
        $array = json_decode($responseContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail("Failed to decode JSON: " . json_last_error_msg());
        }

        if (!isset($array['data']) || !isset($array['data']['endpoint']) || !is_string($array['data']['endpoint'])) {
            throw new \Exception("Endpoint not found in response: " . $responseContent);
        }

        $endpoint = $array['data']['endpoint'];
        $this->repository->findById(new Endpoint($endpoint));

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseCode);
        $this->assertStringContainsString('"ErrorCode":', $responseContent);
        $this->assertStringContainsString('"endpoint":"id_', $responseContent);
    }
}