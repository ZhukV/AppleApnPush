<?php

declare(strict_types=1);

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Protocol;

use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Receiver;

/**
 * All protocols for send notification should implement this interface
 */
interface ProtocolInterface
{
    /**
     * Send notification to receiver
     *
     * @param Receiver     $receiver
     * @param Notification $notification
     * @param bool         $sandbox
     *
     * @throws \Apple\ApnPush\Exception\SendNotification\SendNotificationException
     */
    public function send(Receiver $receiver, Notification $notification, bool $sandbox);
}
