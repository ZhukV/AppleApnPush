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

namespace Apple\ApnPush\Exception\SendNotification;

/**
 * The specified device token was bad. Verify that the request contains
 * a valid token and that the token matches the environment.
 */
class BadDeviceTokenException extends SendNotificationException
{
    public function __construct(string $message = 'Bad device token.')
    {
        parent::__construct($message);
    }
}
