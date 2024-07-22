<?php

namespace Notifications\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use Notifications\Application\Service\Subscription;
use Notifications\Application\Service\SubscriptionRequest;
use Notifications\Application\Service\SubscriptionResponse;
use Notifications\Domain\EventFacade\EventFacade;
use Notifications\Domain\Services\AuthorizationStatus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class OptInController
{
    private AuthorizationStatus $authorizationStatus;

    public function __construct()
    {
        $this->authorizationStatus = new AuthorizationStatus(false);
    }

    #[Route('/api/v1/request/authorization', "subscription", methods: ['POST'])]
    public function setAuthorizationStatus(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        /** @param bool $isAuthorized */
        $isAuthorized = $data['isAuthorized'] ?? false;

        $this->authorizationStatus->setAuthorization($isAuthorized);

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/api/notification/authorization", methods={"GET"})
     */
    public function getAuthorizationStatus(): JsonResponse
    {
        return new JsonResponse(['isAuthorized' => $this->authorizationStatus->isAuthorized()]);
    }
}
