<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

use Apple\ApnPush\Connection\ConnectionInterface;
use Apple\ApnPush\Notification\MessageInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
    public function send(MessageInterface $message);

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger = null);

    /**
     * Get logger
     *
     * @return LoggerInterface
     */
    public function getLogger();

    /**
     * Set event dispatcher
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher = null);

    /**
     * Get event dispatcher
     *
     * @return EventDispatcherInterface|null
     */
    public function getEventDispatcher();

    /**
     * Set whether or not to check for errors
     *
     * @param bool $check
     */
    public function setCheckForErrors($check);

    /**
     * Set try recreate connection if apn server returned empty data
     *
     * @param bool $recreateConnection
     *
     * @return Notification
     */
    public function setRecreateConnection($recreateConnection);
}
