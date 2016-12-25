<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Sender;

use Apple\ApnPush\Model\Message;
use Apple\ApnPush\Model\Receiver;

/**
 * All senders for send push message to device should implement this interface
 */
interface SenderInterface
{
    /**
     * Send message to device
     *
     * @param Receiver $receiver
     * @param Message  $message
     * @param bool     $sandbox
     *
     * @throws \Apple\ApnPush\Exception\SendMessage\SendMessageException
     */
    public function send(Receiver $receiver, Message $message, bool $sandbox = false);
}
