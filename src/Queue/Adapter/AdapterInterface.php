<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Queue\Adapter;

use Apple\ApnPush\Notification\MessageInterface;

/**
 * Interface for control queue adapter
 */
interface AdapterInterface
{
    /**
     * Is adapter can receive next message
     *
     * @return bool
     */
    public function isNextReceive();

    /**
     * Get message from queue
     *
     * @return \Apple\ApnPush\Notification\MessageInterface|null
     */
    public function getMessage();

    /**
     * Add message to queue
     *
     * @param MessageInterface $message
     *
     * @return bool
     */
    public function addMessage(MessageInterface $message);
}
