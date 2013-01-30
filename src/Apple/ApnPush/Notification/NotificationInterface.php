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

use Apple\ApnPush\Connection\ConnectionInterface;
use Apple\ApnPush\Messages\MessageInterface;
use Apple\ApnPush\PayloadFactory\PayloadFactoryInterface;
use Psr\Log\LoggerInterface;

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

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * Get logger
     *
     * @return Logger
     */
    public function getLogger();
}