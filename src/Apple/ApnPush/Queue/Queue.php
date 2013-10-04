<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Queue;

use Apple\ApnPush\Exception\InvalidMessageException;
use Apple\ApnPush\Notification\NotificationInterface;
use Apple\ApnPush\Notification\MessageInterface;
use Apple\ApnPush\Notification\SendException;
use Apple\ApnPush\Queue\Adapter\AdapterInterface;

/**
 * Base queue
 */
class Queue implements QueueInterface
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var NotificationInterface
     */
    protected $notification;

    /**
     * Construct
     *
     * @param AdapterInterface $adapter
     * @param NotificationInterface $notification
     */
    public function __construct(AdapterInterface $adapter = null, NotificationInterface $notification = null)
    {
        $this->adapter = $adapter;
        $this->notification = $notification;
    }

    /**
     * Set queue adapter
     *
     * @param AdapterInterface $adapter
     * @return Queue
     */
    public function setAdapter(AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Get queue adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
       return $this->adapter;
    }

    /**
     * Set notification
     *
     * @param NotificationInterface $notification
     * @return Queue
     */
    public function setNotification(NotificationInterface $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return NotificationInterface
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Send message to queue
     *
     * @param MessageInterface $message
     * @throws \RuntimeException
     * @return bool
     */
    public function sendMessage(MessageInterface $message)
    {
        if (!$this->adapter) {
            throw new \RuntimeException('Can\'t send message to queue. Adapter not found.');
        }

        return $this->adapter->sendMessage($message);
    }

    /**
     * Run receiver instance
     *
     * @throws \RuntimeException
     * @throws InvalidMessageException
     */
    public function runReceiver()
    {
        if (null === $this->adapter) {
            throw new \RuntimeException('Can\'t run receiver. Adapter not found.');
        }

        if (null === $this->notification) {
            throw new \RuntimeException('Can\'t run receiver. Notification not found.');
        }

        while ($this->adapter->isNextReceive()) {
            if ($message = $this->adapter->getMessage()) {
                if (!$message instanceof MessageInterface) {
                    throw new InvalidMessageException(sprintf(
                        'Message must be instance MessageInterface, "%s" given.',
                        is_object($message) ? get_class($message) : gettype($message)
                    ));
                }

                try {
                    $this->notification->send($message);
                } catch (SendException $e) {
                }
            }
        }
    }

}