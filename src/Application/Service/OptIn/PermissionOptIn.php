<?php

namespace Notifications\Application\Service\OptIn;

use Notifications\Domain\Services\StatusClient;

class PermissionOptIn
{
    private PermissionResponse $response;

    public function execute(PermissionRequest $request): void
    {
        $status = $this->askNewPermission($request);

        $this->response = new PermissionResponse(
            $status->getValue()
        );
    }

    private function askNewPermission(PermissionRequest $request): StatusClient
    {
        $status = new StatusClient();
        $status->setValue($request->status);
        return $status;
    }

    public function getResponse(): PermissionResponse
    {
        return $this->response;
    }
}
