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

namespace Apple\ApnPush\Protocol\Http\ExceptionFactory;

use Apple\ApnPush\Exception\SendNotification\SendNotificationException;
use Apple\ApnPush\Protocol\Http\Response;

/**
 * All exception factories for convert response to exception should implement this interface
 */
interface ExceptionFactoryInterface
{
    /**
     * Create new exception by response
     *
     * @param Response $response
     *
     * @return SendNotificationException
     */
    public function create(Response $response): SendNotificationException;
}
