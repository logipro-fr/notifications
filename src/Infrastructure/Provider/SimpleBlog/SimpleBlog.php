<?php

namespace Notifications\Infrastructure\Provider\SimpleBlog;

use Notifications\Application\Service\ApiInterface;
use Notifications\Domain\Entity\Subscriber\Status;
use Notifications\Infrastructure\Provider\ProviderResponse;

use function Safe\fopen;

class SimpleBlog implements ApiInterface
{
    /** @var resource file pointer resource*/
    private $file;
    public function __construct(private string $filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $this->file = fopen($this->filePath, 'c+b');
    }
    public function subscriberApiRequest($subscriber): ProviderResponse
    {
        $subscriber->setStatus(Status::SUBSCRIBED);
        return new ProviderResponse(
            $subscriber->getEndpoint()->__toString()
        );
    }
}
