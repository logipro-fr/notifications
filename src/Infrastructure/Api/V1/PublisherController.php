<?php

namespace Notifications\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use Notifications\Application\Service\Subscription;
use Notifications\Application\Service\SubscriptionRequest;
use Notifications\Application\Service\SubscriptionResponse;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;
use Notifications\Domain\EventFacade\EventFacade;
use Notifications\Domain\Exceptions\EmptySubscriberContentException;
use Notifications\Domain\Services\StatusClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

use function Safe\json_decode;

class PublisherController
{
    private StatusClient $client;

    public function __construct(
        private SubscriberRepositoryInterface $repo,
        private EntityManagerInterface $entityManager
    ) {
        $this->client = new StatusClient();
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
        } catch (EmptySubscriberContentException $e) {
            return $this->writeUnSuccessfulResponse($e);
        }
    }


    private function writeSuccessfulResponse(SubscriptionResponse $publishResponse): JsonResponse
    {
        $this->client->setValue(true);
        return new JsonResponse(
            [
                'success' => $this->client->getValue(),
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

    private function writeUnSuccessFulResponse(Throwable $e): JsonResponse
    {
        $this->client->setValue(false);
        $className = (new \ReflectionClass($e))->getShortName();
        return new JsonResponse(
            [
                'success' => $this->client->getValue(),
                'ErrorCode' => $className,
                'data' => '',
                'message' => $e->getMessage(),
            ],
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
        );
    }


    private function buildPublishRequest(Request $request): SubscriptionRequest
    {
        /** @var string */
        $content = $request->getContent();
         /** @var array<string, array<string>> $data */
        $data = json_decode($content, true);

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
