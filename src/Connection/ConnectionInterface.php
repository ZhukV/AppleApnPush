<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Connection;

/**
 * Interface for connection to Apple push servers
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 * @author Ryan Martinsen <ryan@ryanware.com>
 */
interface ConnectionInterface
{
    /**
     * Default URLs
     */
    const GATEWAY_PUSH_URL              =   'gateway.push.apple.com';
    const GATEWAY_PUSH_PORT             =   2195;

    const GATEWAY_SANDBOX_PUSH_URL      =   'gateway.sandbox.push.apple.com';
    const GATEWAY_SANDBOX_PUSH_PORT     =   2195;

    const FEEDBACK_PUSH_URL             =   'feedback.push.apple.com';
    const FEEDBACK_PUSH_PORT            =   2196;

    const FEEDBACK_SANDBOX_PUSH_URL     =   'feedback.sandbox.push.apple.com';
    const FEEDBACK_SANDBOX_PUSH_PORT    =   2196;

    /**
     * Create connection
     */
    public function connect();

    /**
     * Close connection
     */
    public function close();

    /**
     * Is connection
     *
     * @return bool
     */
    public function is();

    /**
     * Is sandbox mode?
     *
     * @return bool
     */
    public function isSandboxMode();

    /**
     * Write data to connection
     *
     * @param string  $data
     * @param integer $length
     *
     * @return int
     */
    public function write($data, $length);

    /**
     * Is ready read
     *
     * @return bool
     */
    public function isReadyRead();

    /**
     * Set time ready
     * For more information please see http://www.php.net/manual/function.stream-select.php
     *
     * @param integer $second
     * @param integer $uSeconds
     */
    public function setReadTime($second, $uSeconds = 0);

    /**
     * Get time ready
     *
     * @return array
     */
    public function getReadTime();

    /**
     * Read data from connection
     *
     * @param int $length
     *
     * @return string
     */
    public function read($length);

    /**
     * Get connection URL
     *
     * @return string
     */
    public function getUrl();

    /**
     * Get connection port
     *
     * @return integer
     */
    public function getPort();
}
