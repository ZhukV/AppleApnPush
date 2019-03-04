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

/**
 * Send this notification to device.
 */
class Notification implements NotificationInterface
{
    /**
     * @var Payload
     */
    private $payload;

    /**
     * @var ApnId
     */
    private $apnId;

    /**
     * @var Priority
     */
    private $priority;

    /**
     * @var Expiration
     */
    private $expiration;

    /**
     * @var CollapseId
     */
    private $collapseId;

    /**
     * Constructor.
     *
     * @param Payload         $payload
     * @param ApnId|null      $apnId
     * @param Priority|null   $priority
     * @param Expiration|null $expiration
     * @param CollapseId|null $collapseId
     */
    public function __construct(Payload $payload, ApnId $apnId = null, Priority $priority = null, Expiration $expiration = null, CollapseId $collapseId = null)
    {
        $this->payload = $payload;
        $this->priority = $priority;
        $this->apnId = $apnId;
        $this->expiration = $expiration;
        $this->collapseId = $collapseId;
    }

    /**
     * Create new notification with body only
     *
     * @param string $body
     *
     * @return Notification
     */
    public static function createWithBody(string $body): Notification
    {
        return new self(Payload::createWithBody($body));
    }

    /**
     * Set payload
     *
     * @param Payload $payload
     *
     * @return Notification
     */
    public function withPayload(Payload $payload): Notification
    {
        $cloned = clone $this;

        $cloned->payload = $payload;

        return $cloned;
    }

    /**
     * Get payload
     *
     * @return Payload
     */
    public function getPayload(): Payload
    {
        return $this->payload;
    }

    /**
     * Set apn identifier
     *
     * @param ApnId $apnId
     *
     * @return Notification
     */
    public function withApnId(ApnId $apnId = null): Notification
    {
        $cloned = clone $this;

        $cloned->apnId = $apnId;

        return $cloned;
    }

    /**
     * Get identifier of notification
     *
     * @return ApnId
     */
    public function getApnId(): ?ApnId
    {
        return $this->apnId;
    }

    /**
     * Set priority
     *
     * @param Priority $priority
     *
     * @return Notification
     */
    public function withPriority(Priority $priority = null): Notification
    {
        $cloned = clone $this;

        $cloned->priority = $priority;

        return $cloned;
    }

    /**
     * Get priority
     *
     * @return Priority
     */
    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    /**
     * Set expiration
     *
     * @param Expiration $expiration
     *
     * @return Notification
     */
    public function withExpiration(Expiration $expiration = null): Notification
    {
        $cloned = clone $this;

        $cloned->expiration = $expiration;

        return $cloned;
    }

    /**
     * Get expiration
     *
     * @return Expiration
     */
    public function getExpiration(): ?Expiration
    {
        return $this->expiration;
    }

    /**
     * Set the collapse identifier
     *
     * @param CollapseId|null $collapseId
     *
     * @return Notification
     */
    public function withCollapseId(CollapseId $collapseId = null): Notification
    {
        $cloned = clone $this;

        $cloned->collapseId = $collapseId;

        return $cloned;
    }

    /**
     * Get the collapse identifier
     *
     * @return CollapseId|null
     */
    public function getCollapseId(): ?CollapseId
    {
        return $this->collapseId;
    }
}
