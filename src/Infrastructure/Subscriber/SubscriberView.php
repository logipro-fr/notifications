<?php

namespace Notifications\Infrastructure\Subscriber;

class SubscriberView
{
    /**
     * @param array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} $data
     * @return string
     * @throws \RuntimeException if JSON encoding fails
     */
    public function render(array $data): string
    {
        try {
            $json = json_encode($data, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException('Failed to encode data to JSON: ' . $e->getMessage(), 0, $e);
        }

        return $json;
    }
}
