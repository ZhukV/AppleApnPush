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
 * The apns-topic header of the request was not specified and was required.
 * The apns-topic header is mandatory when the client is connected using
 * a certificate that supports multiple topics.
 */
class MissingTopicException extends SendNotificationException
{
    /**
     * Constructor.
     *
     * @param string $message
     */
    public function __construct(string $message = 'Missing topic.')
    {
        parent::__construct($message);
    }
}
