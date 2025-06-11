<?php

declare(strict_types = 1);

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Sender;

use Apple\ApnPush\Exception\SendNotification\SendNotificationException;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Receiver;

interface SenderInterface
{
    /**
     * Send notification to receiver
     *
     * @param Receiver     $receiver
     * @param Notification $notification
     * @param bool         $sandbox
     *
     * @throws SendNotificationException
     */
    public function send(Receiver $receiver, Notification $notification, bool $sandbox = false): void;
}
