<?php

namespace Notifications\Tests\Infrastructure\V1;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\SubscriberRepositoryInterface;
use Notifications\Domain\EventFacade\EventFacade;
use Notifications\Infrastructure\Api\V1\PublisherController;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryDoctrine;
use Phariscope\Event\Tools\SpyListener;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_decode;

class PublisherControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    /** @phpstan-ignore-next-line */
    private SubscriberRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["subscribers"]);

        $this->client = static::createClient(["debug" => false]);
        /** @var SubscriberRepositoryDoctrine */
        $autoInjectedRepo = $this->client->getContainer()->get("subscribers.repository");
        $this->repository = $autoInjectedRepo;
    }

    public function testControllerRouting(): void
    {
        $spy = new SpyListener();
        (new EventFacade())->subscribe($spy);

        $content = json_encode([
            "endpoint" => "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx",
            "expirationTime" => "",
            "keys" => [
                "auth" => "8veJjf8tjO1kbYlX3zOoRw",
                "p256dh" => "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL"
            ],
        ]);

        if ($content === false) {
            $this->fail("Failed to encode JSON.");
        }

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

        $this->assertJson($responseContent, "Response is not valid JSON: " . $responseContent);

        /** @var array<string> */
        $array = json_decode($responseContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail("Failed to decode JSON: " . json_last_error_msg());
        }

         /** @phpstan-ignore-next-line */
        if (!isset($array['data']) || !is_array($array['data'])) {
            $this->fail("Response data does not contain 'data' key or it is not an array: " . $responseContent);
        }

        /** @phpstan-ignore-next-line */
        $endpoint = $array['data']['endpoint'];
        $researchEndpoint = $this->repository->findById(new Endpoint($endpoint));

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseCode);
        $this->assertStringContainsString('"ErrorCode":', $responseContent);
        $this->assertStringContainsString('"endpoint":', $responseContent);
        $this->assertEquals($endpoint, $researchEndpoint->getEndpoint());
    }

    //public function testControllerErrorResponse(): void
    //{
    //    $content = json_encode([
    //        "endpoint" => "",
    //        "expirationTime" => "",
    //        "keys" => [
    //            "auth" => "8veJjf8tjO1kbYlX3zOoRw",
    //            "p256dh" => "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL"
    //        ],
    //    ]);
//
    //    if ($content === false) {
    //        $this->fail("Failed to encode JSON.");
    //    }
//
    //    $this->client->request(
    //        "POST",
    //        "/api/v1/subscriber/register",
    //        [],
    //        [],
    //        ['CONTENT_TYPE' => 'application/json'],
    //        $content
    //    );
//
    //    /** @var string $responseContent */
    //    $responseContent = $this->client->getResponse()->getContent();
    //    $responseCode = $this->client->getResponse()->getStatusCode();
//
    //    if ($responseContent === "") {
    //        $this->fail("Failed to get response content.");
    //    }
//
    //    $this->assertEquals(500, $responseCode);
    //    $this->assertStringContainsString('"success":false', $responseContent);
    //    $this->assertStringContainsString('"ErrorCode":"EmptySubscriberContentException"', $responseContent);
    //}

    public function testExecute(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->name = 'Notifications\Domain\Entity\Subscriber\Subscriber';

        $entityManager->method('getClassMetadata')
                      ->willReturn($classMetadata);

        $subscriberRepository = new SubscriberRepositoryDoctrine($entityManager);

        $controller = new PublisherController($subscriberRepository, $entityManager);

        $content = json_encode([
            "endpoint" => "https://updates.push.services.mozilla.com/wpush/v2/gAAAAABmSxoTx",
            "expirationTime" => "",
            "keys" => [
                "auth" => "8veJjf8tjO1kbYlX3zOoRw",
                "p256dh" => "BF1Z6uz9IZRoqbzyW3GPIYpld0vhSBWUaDslQQWqL"
            ],
        ]);

        if ($content === false) {
            $this->fail("Failed to encode JSON.");
        }

        $request = Request::create(
            "/api/v1/subscriber",
            "POST",
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );

        $response = $controller->execute($request);
        $responseContent = $response->getContent();

        if ($responseContent === "") {
            $this->fail("Failed to get response content.");
        }

        $this->assertJson((string)$responseContent);
    }
}