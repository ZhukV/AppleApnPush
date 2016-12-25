<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Exception\SendMessage;

/**
 * No provider certificate was used to connect to APNs and
 * Authorization header was missing or no provider token was specified.
 */
class MissingProviderTokenException extends SendMessageException
{
    /**
     * Constructor.
     *
     * @param string $message
     */
    public function __construct(string $message = 'Missing provider token.')
    {
        parent::__construct($message);
    }
}
