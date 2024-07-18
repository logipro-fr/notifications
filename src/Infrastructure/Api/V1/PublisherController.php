<?php

namespace Notifications\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use Notifications\Application\Service\Subscription;
use Notifications\Application\Service\SubscriptionRequest;
use Notifications\Application\Service\SubscriptionResponse;
use Notifications\Domain\Entity\Subscriber\SubscriberRepositoryInterface;
use Notifications\Domain\EventFacade\EventFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

use function Safe\json_decode;

class PublisherController
{
    public function __construct(
        private SubscriberRepositoryInterface $repo,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/api/v1/subscriber/register', name: 'subscription', methods: ['POST'])]
    public function execute(Request $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            $publishRequest = $this->buildPublishRequest($request);
            $service = new Subscription($this->repo);
            $service->execute($publishRequest);
            (new EventFacade())->distribute();
            $publishResponse = $service->getResponse();
            $this->entityManager->flush();
            return $this->writeSuccessfulResponse($publishResponse);
        });
    }

    private function handleRequest(callable $function): JsonResponse
    {
        try {
            return $function();
        } catch (Throwable $e) {
            error_log($e->getMessage());
        }
    }

    private function writeSuccessfulResponse(SubscriptionResponse $publishResponse): JsonResponse
    {
        return new JsonResponse(
            [
                'success' => true,
                'ErrorCode' => "",
                'data' => [
                    'endpoint' => $publishResponse->endpoint,
                    'expirationTime' => $publishResponse->expirationTime,
                    'keys' => $publishResponse->keys
                ],
                'message' => "",
            ],
            201
        );
    }

    private function buildPublishRequest(Request $request): SubscriptionRequest
    {
        /** @var string */
        $content = $request->getContent();
        /** @var array<string, mixed>|null */
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }
        
        /** @var string */
        $endpoint = $data['endpoint'];
        /** @var string */
        $expirationTime = $data['expirationTime'] ?? '';
        /** @var string */
        $authkey = $data['keys']['auth'];
        /** @var string */
        $p256dhkey = $data['keys']['p256dh'];

        return new SubscriptionRequest($endpoint, $expirationTime, $authkey, $p256dhkey);
    }
}
