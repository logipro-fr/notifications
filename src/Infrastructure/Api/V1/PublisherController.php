<?php

namespace Notifications\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use Notifications\Application\Service\Subscription\Subscription;
use Notifications\Application\Service\Subscription\SubscriptionRequest;
use Notifications\Application\Service\Subscription\SubscriptionResponse;
use Notifications\Application\Service\Unsubscription\Unsubscription;
use Notifications\Application\Service\Unsubscription\UnsubscriptionRequest;
use Notifications\Application\Service\Unsubscription\UnsubscriptionResponse;
use Notifications\Domain\EventFacade\EventFacade;
use Notifications\Domain\Exceptions\EmptySubscriberContentException;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\Keys;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;
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

    #[Route('/api/v1/subscriber/manager', name: 'subscription', methods: ['POST'])]
    public function execute(Request $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            $publishRequest = $this->buildPublishRequest($request);
            $service = new Subscription($this->repo);
            $service->execute($publishRequest);
            (new EventFacade())->distribute();
            $publishResponse = $service->getResponse();
            $this->entityManager->flush();
            return $this->writeRegisterSuccessfulResponse($publishResponse);
        });
    }

    #[Route('/api/v1/subscriber/manager', name: 'unsubscription', methods: ['DELETE'])]
    public function unsubscribe(Request $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            $unsubscribeRequest = $this->buildUnsubscribeRequest($request);
            $service = new Unsubscription($this->repo);
            $service->execute($unsubscribeRequest);
            (new EventFacade())->distribute();
            $unsubscribeResponse = $service->getResponse();
            $this->entityManager->flush();
            return $this->writeUnsubscribeSuccessfulResponse($unsubscribeResponse);
        });
    }

    private function handleRequest(callable $function): JsonResponse
    {
        try {
            return $function();
        } catch (EmptySubscriberContentException $e) {
            return $this->writeUnSuccessfulResponse($e);
        } catch (Throwable $e) { // Catch all other exceptions to ensure JSON response
            return new JsonResponse([
                'success' => false,
                'ErrorCode' => 'ServerError',
                'data' => '',
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    private function writeRegisterSuccessfulResponse(SubscriptionResponse $publishResponse): JsonResponse
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

    private function writeUnsubscribeSuccessfulResponse(UnsubscriptionResponse $unsubscribeResponse): JsonResponse
    {
        $this->client->setValue(true);
        return new JsonResponse(
            [
                'success' => $this->client->getValue(),
                'ErrorCode' => "",
                'data' => [
                    'endpoint' => $unsubscribeResponse->endpoint,
                    'expirationTime' => $unsubscribeResponse->expirationTime,
                    'keys' => $unsubscribeResponse->keys
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

    private function buildUnsubscribeRequest(Request $request): UnsubscriptionRequest
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

        $keys = new Keys($authkey, $p256dhkey);
        return new UnsubscriptionRequest($endpoint, $expirationTime, $keys);
    }

}