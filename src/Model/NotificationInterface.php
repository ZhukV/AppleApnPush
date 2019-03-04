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
interface NotificationInterface
{
    /**
     * Set payload
     *
     * @param Payload $payload
     *
     * @return Notification
     */
    public function withPayload(Payload $payload) : Notification;

    /**
     * Get payload
     *
     * @return Payload
     */
    public function getPayload() : Payload;

    /**
     * Set apn identifier
     *
     * @param ApnId $apnId
     *
     * @return Notification
     */
    public function withApnId(ApnId $apnId = null) : Notification;

    /**
     * Get identifier of notification
     *
     * @return ApnId
     */
    public function getApnId() : ?ApnId;

    /**
     * Set priority
     *
     * @param Priority $priority
     *
     * @return Notification
     */
    public function withPriority(Priority $priority = null) : Notification;

    /**
     * Get priority
     *
     * @return Priority
     */
    public function getPriority() : ?Priority;

    /**
     * Set expiration
     *
     * @param Expiration $expiration
     *
     * @return Notification
     */
    public function withExpiration(Expiration $expiration = null) : Notification;

    /**
     * Get expiration
     *
     * @return Expiration
     */
    public function getExpiration() : ?Expiration;

    /**
     * Set the collapse identifier
     *
     * @param CollapseId|null $collapseId
     *
     * @return Notification
     */
    public function withCollapseId(CollapseId $collapseId = null) : Notification;

    /**
     * Get the collapse identifier
     *
     * @return CollapseId|null
     */
    public function getCollapseId() : ?CollapseId;
}
