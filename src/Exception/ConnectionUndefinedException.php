<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Exception;

/**
 * Connection not found exception
 */
class ConnectionUndefinedException extends ApnPushException
{
    /**
     * Construct
     *
     * @param string     $message
     * @param integer    $code
     * @param \Exception $prev
     */
    public function __construct($message = 'Connection not found.', $code = 0, \Exception $prev = null)
    {
        parent::__construct($message, $code, $prev);
    }
}
