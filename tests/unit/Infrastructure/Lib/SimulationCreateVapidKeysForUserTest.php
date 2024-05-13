<?php

namespace Notifications\Tests\Insfrastructure\Lib;

use Notifications\Application\Service\FileManager\ObtainData;
use Notifications\Infrastructure\Lib\Pushlib;
use Notifications\Infrastructure\Lib\RequestLib;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class SimulationCreateVapidKeysForUserTest extends TestCase
{
    public function testRequest(): void
    {
        $client = $this->createMockHttpClient(Pushlib::RESPONSE);
        $pushlibtest = new Pushlib($client);
        $requestLib = new RequestLib('create my access');
        $response = $pushlibtest->request($requestLib);
        $key = (new ObtainData())->readJSON(__DIR__, Pushlib::RESPONSE);
        $this->assertEquals((new ObtainData())->printFakePublicKey($key), $response->message);
    }

    private function createMockHttpClient(string $filename): MockHttpClient
    {
        $content = file_get_contents(__DIR__ . '/resources/' . $filename);

        if ($content === false) {
            throw new \RuntimeException("Failed to read file: " . $filename);
        }
        $responses = [
            new MockResponse($content),
        ];
        return new MockHttpClient($responses, 'https://www.example.com');
    }
}
