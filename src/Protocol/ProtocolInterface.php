<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Protocol;

use Apple\ApnPush\Model\Message;
use Apple\ApnPush\Model\Receiver;

/**
 * All protocols for send push notification should implement this interface
 */
interface ProtocolInterface
{
    /**
     * Send message
     *
     * @param Receiver $receiver
     * @param Message  $message
     * @param bool     $sandbox
     *
     * @throws \Apple\ApnPush\Exception\SendMessage\SendMessageException
     */
    public function send(Receiver $receiver, Message $message, bool $sandbox);
}
