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
 * The provider token is not valid or the token signature could not be verified.
 */
class InvalidProviderTokenException extends SendNotificationException
{
    public function __construct(string $message = 'Invalid provider token.')
    {
        parent::__construct($message);
    }
}
