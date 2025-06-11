<?php

declare(strict_types = 1);

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Model;

class Notification
{
    private Payload $payload;
    private ?ApnId $apnId;
    private ?Priority $priority;
    private ?Expiration $expiration;
    private ?CollapseId $collapseId;
    private ?PushType $pushType;

    public function __construct(Payload $payload, ?ApnId $apnId = null, ?Priority $priority = null, ?Expiration $expiration = null, ?CollapseId $collapseId = null, ?PushType $pushType = null)
    {
        $this->payload = $payload;
        $this->priority = $priority;
        $this->apnId = $apnId;
        $this->expiration = $expiration;
        $this->collapseId = $collapseId;
        $this->pushType = $pushType;
    }

    public static function createWithBody(string $body): self
    {
        return new self(Payload::createWithBody($body));
    }

    public function withPayload(Payload $payload): self
    {
        $cloned = clone $this;

        $cloned->payload = $payload;

        return $cloned;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    public function withApnId(?ApnId $apnId = null): self
    {
        $cloned = clone $this;

        $cloned->apnId = $apnId;

        return $cloned;
    }

    public function getApnId(): ?ApnId
    {
        return $this->apnId;
    }

    public function withPriority(?Priority $priority = null): self
    {
        $cloned = clone $this;

        $cloned->priority = $priority;

        return $cloned;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function withExpiration(?Expiration $expiration = null): self
    {
        $cloned = clone $this;

        $cloned->expiration = $expiration;

        return $cloned;
    }

    public function getExpiration(): ?Expiration
    {
        return $this->expiration;
    }

    public function withCollapseId(?CollapseId $collapseId = null): self
    {
        $cloned = clone $this;

        $cloned->collapseId = $collapseId;

        return $cloned;
    }

    public function getCollapseId(): ?CollapseId
    {
        return $this->collapseId;
    }

    public function withPushType(?PushType $pushType): self
    {
        $cloned = clone $this;

        $cloned->pushType = $pushType;

        return $cloned;
    }

    public function getPushType(): ?PushType
    {
        return $this->pushType;
    }
}
