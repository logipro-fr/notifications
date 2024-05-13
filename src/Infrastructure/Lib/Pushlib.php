<?php

namespace Notifications\Infrastructure\Lib;

use Notifications\Application\Service\RequestInterface;
use Notifications\Infrastructure\Lib\PushLibInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_decode;

class Pushlib implements PushLibInterface
{
    public const RESPONSE = 'responseCreateKey.json';

    public function __construct(private HttpClientInterface $client)
    {
    }

    public function request(RequestInterface $request): ResponseLib
    {
        $content = <<<EOF
        {
            "model": "web-push",
            "messages": [
            ]
        }
        EOF;

        $response = $this->client->request(
            'POST',
            'https://example.com',
            [

            'headers' => ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '],
            'body' => $content
            ]
        );
        $contentJson = $response->getContent();
        /** @var \stdClass{"choices": array<int,\stdClass>} $content */
        $content = json_decode($contentJson);
        $choices = $content->choices;
        $messageContent = $choices[0]->message->content ;
        //$content['choices'][0]['message']['content'];
        $contentmodel = strval($messageContent);
        return new ResponseLib($contentmodel);
    }
}
