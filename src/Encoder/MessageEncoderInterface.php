<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Encoder;

use Apple\ApnPush\Model\Message;

/**
 * All message encoder should implement this interface
 */
interface MessageEncoderInterface
{
    /**
     * Encode push message for next send to Apple Push Notification Service
     *
     * @param Message $message
     *
     * @return string
     */
    public function encode(Message $message) : string;
}
