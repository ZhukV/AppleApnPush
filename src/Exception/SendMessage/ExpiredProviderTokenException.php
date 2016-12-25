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
 * The provider token is stale and a new token should be generated.
 */
class ExpiredProviderTokenException extends SendMessageException
{
    /**
     * Constructor.
     *
     * @param string $message
     */
    public function __construct(string $message = 'Expired provider token.')
    {
        parent::__construct($message);
    }
}
