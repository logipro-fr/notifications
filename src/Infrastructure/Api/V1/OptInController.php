<?php

namespace Notifications\Infrastructure\Api\V1;

use Notifications\Application\Service\PermissionOptIn;
use Notifications\Application\Service\PermissionRequest;
use Notifications\Domain\EventFacade\EventFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use function Safe\json_decode;

class OptInController
{
    #[Route('/api/v1/subscriber/authorization', name: 'authorization', methods: ['POST'])]
    public function execute(Request $request): JsonResponse
    {
        $permissionRequest = $this->buildPermissionRequest($request);
        $service = new PermissionOptIn();
        $service->execute($permissionRequest);
        (new EventFacade())->distribute();
        $response = $service->getResponse();
        if ($response->status !== true || !isset($response->status)) {
            return new JsonResponse(['success' => false, 'ErrorCode' => 'AuthorizationDenied'], 403);
        }
        return new JsonResponse(['success' => true], 200);
    }

    private function buildPermissionRequest(Request $request): PermissionRequest
    {
        /** @var string */
        $content = $request->getContent();
         /** @var array<bool>> $data */
        $data = json_decode($content, true);

        /** @var bool */
        $status = $data['AuthorizedStatus'];

        return new PermissionRequest($status);
    }
}
