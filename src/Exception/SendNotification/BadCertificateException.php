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
 * The certificate was bad.
 */
class BadCertificateException extends SendNotificationException
{
    public function __construct(string $message = 'Bad certificate.')
    {
        parent::__construct($message);
    }
}
