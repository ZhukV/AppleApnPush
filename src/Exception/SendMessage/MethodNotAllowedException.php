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
 * The specified :method was not POST.
 */
class MethodNotAllowedException extends SendMessageException
{
    /**
     * Constructor.
     *
     * @param string $message
     */
    public function __construct(string $message = 'Method not allowed.')
    {
        parent::__construct($message);
    }
}
