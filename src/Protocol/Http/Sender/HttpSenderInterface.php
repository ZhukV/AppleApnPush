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
use Apple\ApnPush\Protocol\Http\Sender\Exception\HttpSenderException;

/**
 * All HTTP senders should implement this interface
 */
interface HttpSenderInterface
{
    /**
     * Send HTTP request
     *
     * @return void
     *
     * @throws HttpSenderException
     */
    public function send(): void;

    /**
     * @param Request  $request
     *
     * @param callable $callback
     *
     */
    public function addRequest(Request $request, callable $callback);

    /**
     * Set max concurrent requests.
     *
     * @param int $maxRequests
     *
     * @return HttpSenderInterface
     */
    public function maxRequests(int $maxRequests): HttpSenderInterface;

    /**
     * Set the global requests timeout.
     *
     * @param int $timeout
     *
     * @return HttpSenderInterface
     */
    public function timeout(int $timeout): HttpSenderInterface;
}
