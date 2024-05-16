<?php

namespace Notifications\Infrastructure\PushLib;

use Notifications\Application\Service\RequestInterface;
use Notifications\Infrastructure\PushLib\PushLibInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_decode;

class Pushlib implements PushLibInterface
{
    public const RESPONSE = 'responseCreateKey.json';
    private const METHOD = 'POST';
    private const URL = 'www.example.com';
    private const HEADER = ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '];

    public function __construct(private HttpClientInterface $client)
    {
    }

    public function request(RequestInterface $request): ResponseLib
    {
        $content = <<<EOF
        {
            "model": "web-push",
            "messages": []
        }
        EOF;
        $array = $this->getOption($content);
        $response = $this->client->request(
            self::METHOD,
            self::URL,
            $array
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

    /**
     * @param string $content
     * @return array<mixed>
     */
    public function getOption(string $content): array
    {
        $headerTag = 'headers';
        $bodyTag = 'body';
        return [
            $headerTag => self::HEADER,
            $bodyTag => $content
        ];
    }
}
