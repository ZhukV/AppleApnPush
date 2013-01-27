<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Exceptions;

/**
 * Device token not found
 */
class DeviceTokenNotFoundException extends ApnPushException
{
    /**
     * Construct
     */
    public function __construct($message = 'Device token not found.', $code = 0, \Exception $prev = NULL)
    {
        parent::__construct($message, $code, $prev);
    }
}