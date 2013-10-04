<?php

/**
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
 * Simple array adapter.
 * Attention: this adapter can't be start use with server->node system!
 */
class ArrayAdapter implements AdapterInterface, \Countable
{
    /**
     * @var array|MessageInterface[]
     */
    private $_messages = array();

    /**
     * Is adapter can receive next message
     *
     * @return bool
     */
    public function isNextReceive()
    {
        return count($this->_messages);
    }

    /**
     * Get message from queue
     *
     * @return \Apple\ApnPush\Notification\MessageInterface|null
     */
    public function getMessage()
    {
        return array_shift($this->_messages);
    }

    /**
     * Add message to queue
     *
     * @param MessageInterface $message
     * @return bool
     */
    public function addMessage(MessageInterface $message)
    {
        return array_push($this->_messages, $message);
    }

    /**
     * Get count messages
     *
     * @return int
     */
    public function count()
    {
        return count($this->_messages);
    }
}