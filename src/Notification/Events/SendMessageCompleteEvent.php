<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification\Events;

use Apple\ApnPush\Notification\MessageInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Send message complete event
 */
class SendMessageCompleteEvent extends Event
{
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * Construct
     *
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }
}
