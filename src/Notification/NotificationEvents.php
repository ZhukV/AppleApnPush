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

/**
 * Available events
 */
final class NotificationEvents
{
    const SEND_MESSAGE_COMPLETE         = 'apple.apn_push.send_message.complete';
    const SEND_MESSAGE_ERROR            = 'apple.apn_push.send_message.error';

    /**
     * Disable constructor
     */
    private function __construct()
    {
    }
}
