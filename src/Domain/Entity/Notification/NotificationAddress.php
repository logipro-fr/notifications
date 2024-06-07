<?php

namespace Notifications\Domain\Entity\Notification;

class NotificationAddress
{
    public function __construct(
        private string  $endpoint,
        private ?string $publicKey = null,
        private ?string $authToken = null,
        private ?string $contentEncoding = null) 
    {
        if($publicKey || $authToken || $contentEncoding) {
            $supportedContentEncodings = ['aesgcm', 'aes128gcm'];
            if ($contentEncoding && !in_array($contentEncoding, $supportedContentEncodings, true)) {
                throw new \ErrorException('This content encoding ('.$contentEncoding.') is not supported.');
            }
            $this->contentEncoding = $contentEncoding ?: "aesgcm";
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * {@inheritDoc}
     */
    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * {@inheritDoc}
     */
    public function getContentEncoding(): ?string
    {
        return $this->contentEncoding;
    }
}
