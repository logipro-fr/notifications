<?php

namespace Notifications\Tests\Domain\Model\Subscriber;

use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Exceptions\EmptySubscriberContentException;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    private string $url;

    protected function setUp(): void
    {
        $this->url = "https://example.com";
    }

    public function testConstructWithValidUrl(): void
    {

        $endpoint = new Endpoint($this->url);

        $this->assertInstanceOf(Endpoint::class, $endpoint);
        $this->assertEquals($this->url, (string)$endpoint);
    }

    public function testConstructWithEmptyUrlThrowsException(): void
    {
        $this->expectException(EmptySubscriberContentException::class);
        $this->expectExceptionMessage(EmptySubscriberContentException::MESSAGE);
        $this->expectExceptionCode(EmptySubscriberContentException::ERROR_CODE);

        new Endpoint("");
    }

    public function testToString(): void
    {
        $endpoint = new Endpoint($this->url);

        $this->assertEquals($this->url, $endpoint->__toString());
    }

    public function testEqualsWithSameUrl(): void
    {
        $endpoint1 = new Endpoint($this->url);
        $endpoint2 = new Endpoint($this->url);

        $this->assertTrue($endpoint1->equals($endpoint2));
    }

    public function testEqualsWithDifferentUrl(): void
    {
        $url1 = "https://example.com";
        $url2 = "https://anotherexample.com";
        $endpoint1 = new Endpoint($url1);
        $endpoint2 = new Endpoint($url2);

        $this->assertFalse($endpoint1->equals($endpoint2));
    }
}
