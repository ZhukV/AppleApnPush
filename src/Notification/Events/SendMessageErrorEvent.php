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
use Apple\ApnPush\Notification\SendException;
use Symfony\Component\EventDispatcher\Event;

/**
 * Send message error event
 */
class SendMessageErrorEvent extends Event
{
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @var SendException
     */
    private $exception;

    /**
     * Construct
     *
     * @param MessageInterface $message
     * @param SendException    $exception
     */
    public function __construct(MessageInterface $message, SendException $exception)
    {
        $this->message = $message;
        $this->exception = $exception;
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

    /**
     * Get exception
     *
     * @return SendException
     */
    public function getException()
    {
        return $this->exception;
    }
}
