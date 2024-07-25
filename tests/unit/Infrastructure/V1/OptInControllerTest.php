<?php

namespace Notifications\Tests\Infrastructure\V1;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use Notifications\Domain\EventFacade\EventFacade;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\json_decode;

class OptInControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private MockObject $eventFacade;

    public function setUp(): void
    {
        $this->initDoctrineTester();

        $this->client = static::createClient(["debug" => false]);
        $this->eventFacade = $this->createMock(EventFacade::class);
        $this->client->getContainer()->set(EventFacade::class, $this->eventFacade);
    }

    public function testAuthorization(): void
    {
        $data = ["AuthorizedStatus" => true];
        $jsonData = json_encode($data);

        if ($jsonData === false) {
            $this->fail("Failed to encode JSON.");
        }

        $this->client->request(
            'POST',
            '/api/v1/subscriber/authorization',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonData
        );
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();

        if ($responseContent === false) {
            $this->fail("Failed to get response content.");
        }

        $this->assertEquals(200, $responseCode);
        $this->assertJson($responseContent, "Response is not valid JSON: " . $responseContent);

        $array = json_decode($responseContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail("Failed to decode JSON: " . json_last_error_msg());
        }

        $this->assertIsArray($array);
        $this->assertArrayHasKey('success', $array);
        $this->assertTrue($array['success']);
    }

    public function testRefusal(): void
    {
        $data = ['AuthorizedStatus' => false];
        $jsonData = json_encode($data);

        if ($jsonData === false) {
            $this->fail("Failed to encode JSON.");
        }

        $this->client->request(
            'POST',
            '/api/v1/subscriber/authorization',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonData
        );

        $response = $this->client->getResponse();
        $responseContent = $response->getContent();
        $responseCode = $response->getStatusCode();

        $this->assertEquals(403, $responseCode);

        if ($responseContent === false) {
            $this->fail("Failed to get response content.");
        }

        $this->assertJson($responseContent, "Response is not valid JSON: " . $responseContent);

        $array = json_decode($responseContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail("Failed to decode JSON: " . json_last_error_msg());
        }

        $this->assertIsArray($array);
        $this->assertArrayHasKey('success', $array);
        $this->assertFalse($array['success']);
        $this->assertArrayHasKey('ErrorCode', $array);
        $this->assertEquals('AuthorizationDenied', $array['ErrorCode']);
    }
}
