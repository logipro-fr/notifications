<?php

namespace Notifications\Tests\Integration\Infrastructure\Lib;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\SubscriberRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\json_encode;

class OptInControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private SubscriberRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->initDoctrineTester();

        $this->client = static::createClient(["debug" => false]);
    }

    public function testAuthorization(): void
    {
        $data = ["AuthorizedStatus" => true];

        $this->client->request(
            'POST',
            '/api/v1/subscriber/authorization',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
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

        $this->assertArrayHasKey('success', $array);
        $this->assertTrue($array['success']);
    }

    public function testRefusal(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/subscriber/authorization',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['AuthorizedStatus' => false])
        );

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
}
