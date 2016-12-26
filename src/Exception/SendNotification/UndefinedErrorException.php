<?php

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
 * Throw this exception if system can not resolve error after send notification.
 */
class UndefinedErrorException extends SendNotificationException
{
    /**
     * Constructor.
     *
     * @param string $message
     */
    public function __construct(string $message = 'Undefined error.')
    {
        parent::__construct($message);
    }
}
