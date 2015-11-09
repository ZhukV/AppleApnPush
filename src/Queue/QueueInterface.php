<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Queue;

use Apple\ApnPush\Notification\MessageInterface;
use Apple\ApnPush\Notification\NotificationInterface;
use Apple\ApnPush\Queue\Adapter\AdapterInterface;

/**
 * All queue should be implements of this interface
 */
interface QueueInterface
{
    /**
     * Set queue adapter
     *
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter = null);

    /**
     * Get queue adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter();

    /**
     * Set notification
     *
     * @param NotificationInterface $notification
     */
    public function setNotification(NotificationInterface $notification = null);

    /**
     * Get notification
     *
     * @return NotificationInterface
     */
    public function getNotification();

    /**
     * Set notification error handler
     *
     * @param callable $notificationErrorHandler
     */
    public function setNotificationErrorHandler($notificationErrorHandler);

    /**
     * Get notification error handler
     *
     * @return callable
     */
    public function getNotificationErrorHandler();

    /**
     * Add message to queue
     *
     * @param MessageInterface $message
     *
     * @return bool
     */
    public function addMessage(MessageInterface $message);

    /**
     * Run receiver instance
     */
    public function runReceiver();
}
