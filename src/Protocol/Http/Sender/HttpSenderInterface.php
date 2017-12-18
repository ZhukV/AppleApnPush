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

namespace Apple\ApnPush\Protocol\Http\Sender;

use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Response;
use Apple\ApnPush\Protocol\Http\Sender\Exception\HttpSenderException;

/**
 * All HTTP senders should implement this interface
 */
interface HttpSenderInterface
{
    /**
     * Send HTTP request
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws HttpSenderException
     */
    public function send(Request $request): Response;

    /**
     * Close connection
     */
    public function close(): void;
}
