<?php

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
class Notification
{
    /**
     * @var Payload
     */
    private $payload;

    /**
     * @var ApnId
     */
    private $id;

    /**
     * @var Priority
     */
    private $priority;

    /**
     * @var Expiration
     */
    private $expiration;

    /**
     * Constructor.
     *
     * @param Payload         $payload
     * @param ApnId|null      $id
     * @param Priority|null   $priority
     * @param Expiration|null $expiration
     */
    public function __construct(
        Payload $payload,
        ApnId $id = null,
        Priority $priority = null,
        Expiration $expiration = null
    ) {
        $this->payload = $payload;
        $this->priority = $priority ?: Priority::fromNull();
        $this->id = $id ?: ApnId::fromNull();
        $this->expiration = $expiration ?: Expiration::fromNull();
    }

    /**
     * Set payload
     *
     * @param Payload $payload
     *
     * @return Notification
     */
    public function withPayload(Payload $payload)
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
    public function getPayload()
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
    public function withId(ApnId $apnId) : Notification
    {
        $cloned = clone $this;

        $cloned->id = $apnId;

        return $cloned;
    }

    /**
     * Get identifier of message
     *
     * @return ApnId
     */
    public function getId() : ApnId
    {
        return $this->id;
    }

    /**
     * Set priority
     *
     * @param Priority $priority
     *
     * @return Notification
     */
    public function withPriority(Priority $priority) : Notification
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
    public function getPriority() : Priority
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
    public function withExpiration(Expiration $expiration) : Notification
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
    public function getExpiration() : Expiration
    {
        return $this->expiration;
    }
}
