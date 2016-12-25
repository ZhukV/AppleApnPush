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
 * The collapse identifier exceeds the maximum allowed size.
 */
class BadCollapseIdException extends SendMessageException
{
    /**
     * Constructor.
     *
     * @param string $message
     */
    public function __construct(string $message = 'Bad collapse id.')
    {
        parent::__construct($message);
    }
}
