<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

use Apple\ApnPush\Connection\ConnectionInterface,
    Apple\ApnPush\Messages\MessageInterface,
    Apple\ApnPush\PayloadFactory\PayloadFactoryInterface;

/**
 * Interface for control notification system
 */
interface NotificationInterface
{
    /**
     * Set connection
     *
     * @param ConnectionInterface $connection
     */
    public function setConnection(ConnectionInterface $connection);

    /**
     * Get connection
     *
     * @return ConnectionInterface
     */
    public function getConnection();

    /**
     * Set payload factory
     *
     * @param PayloadFactoryInterface $payloadFactory
     */
    public function setPayloadFactory(PayloadFactoryInterface $payloadFactory);

    /**
     * Get payload factory
     *
     * @return PayloadFactoryInterface
     */
    public function getPayloadFactory();

    /**
     * Send message
     *
     * @param MessageInterface $message
     */
    public function sendMessage(MessageInterface $message);
}