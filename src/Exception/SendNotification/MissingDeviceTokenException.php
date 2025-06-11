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
 * The device token is not specified in the request :path.
 * Verify that the :path header contains the device token.
 */
class MissingDeviceTokenException extends SendNotificationException
{
    public function __construct(string $message = 'Missing device token.')
    {
        parent::__construct($message);
    }
}
