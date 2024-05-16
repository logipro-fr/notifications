<?php

namespace Notifications\Tests\Infrastructure\PushLib;

use Notifications\Infrastructure\FileManager\ObtainData;
use Notifications\Infrastructure\PushLib\Pushlib;
use Notifications\Infrastructure\PushLib\RequestLib;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class PushlibTest extends TestCase
{
    private const URL = 'http://example.com/';

    public function testRequest(): void
    {
        $client = $this->createMockHttpClient(Pushlib::RESPONSE);
        $pushlibtest = new Pushlib($client);

        $requestLib = new RequestLib('create my access');

        $response = $pushlibtest->request($requestLib);

        $key = (new ObtainData())->readJSON(__DIR__, Pushlib::RESPONSE);
        $this->assertEquals((new ObtainData())->printFakePublicKey($key), $response->message);
        $this->assertIsArray($key);
    }


    private function createMockHttpClient(string $filename): MockHttpClient
    {
        $responses = [
            new MockResponse($this->getResponseContent($filename)),
        ];
        return new MockHttpClient($responses, self::URL);
    }

    private function getResponseContent(string $filename): \Generator
    {
        $content = file_get_contents(__DIR__ . '/resources/' . $filename);
        if ($content === false) {
            yield false;
        } else {
            yield $content;
        }
    }

    public function testVerifyHeader(): void
    {
        $content = <<<EOF
        {
            "model": "web-push",
            "messages": []
        }
        EOF;
        $client = $this->createMockHttpClient(Pushlib::RESPONSE);
        $pushlibtest = new Pushlib($client);
        $result = $pushlibtest->getOption($content);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('headers', $result);
    }
}
