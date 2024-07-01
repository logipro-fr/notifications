<?php

namespace Notifications\Tests\Integration\Infrastructure\Lib;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\SubscriberRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublishercontrollerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private SubscriberRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["subscribers"]);

        $this->client = static::createClient(["debug" => false]);

        /** @var PostRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("subscribers.repository");
        $this->repository = $autoInjectedRepo;
    }
    public function testControllerErrorResponse(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/post/publish",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "website" => "",
                "endpoint" => "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx",
                "expiration_time" => "null",
                "keys" => "{
                    auth: 8veJjf8tjO1kbYlX3zOoRw,
                    p256dh: BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL_pEdR9rt1xgJdVCNJYm6_8AJ6wP2AvfjNHKehcqbSveo0c
                }",
            
            ])
        );
        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();

        $this->assertResponseIsUnprocessable();
        $this->assertEquals(422, $responseCode);
        $this->assertStringContainsString('"success":false', $responseContent);
        $this->assertStringContainsString('"ErrorCode":"BadSocialNetworksParameterException"', $responseContent);
        $this->assertStringContainsString(
            '"message":"Invalid social network"',
            $responseContent
        );
    }
    public function testControllerRouting(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/post/publish",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "website" => "",
                "endpoint" => "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx",
                "expiration_time" => "null",
                "keys" => "{
                    auth: 8veJjf8tjO1kbYlX3zOoRw,
                    p256dh: BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL_pEdR9rt1xgJdVCNJYm6_8AJ6wP2AvfjNHKehcqbSveo0c
                }",
            ])
        );
        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();

        /** @var array<mixed,array<mixed>> */
        $array = json_decode($responseContent, true);
        /** @var string */
        $endpoint = $array['data']['endpoint'];
        $post = $this->repository->findById(new Endpoint($endpoint));

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseCode);
        $this->assertStringContainsString('"ErrorCode":', $responseContent);
        $this->assertStringContainsString('"endpoint":"id_', $responseContent);
    }
}