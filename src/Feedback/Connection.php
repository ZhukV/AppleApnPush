<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Feedback;

use Apple\ApnPush\Connection\Connection as BaseConnection;
use Apple\ApnPush\Connection\ConnectionInterface;

/**
 * Connection for the Apple Push Notification Feedback Service
 *
 * @author Ryan Martinsen <ryan@ryanware.com>
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Connection extends BaseConnection
{
    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        $this->socketClientFlags = STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT;

        parent::connect();
    }

    /**
     * {@inheritDoc}
     */
    public function write($binaryData, $length = null)
    {
        throw new \BadMethodCallException('Cannot write to a feedback connection.');
    }

    /**
     * {@inheritDoc}
     */
    public function isReadyRead()
    {
        throw new \BadMethodCallException('isReadyRead() is not used for feedback connections.');
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl()
    {
        return $this->sandboxMode
            ? ConnectionInterface::FEEDBACK_SANDBOX_PUSH_URL
            : ConnectionInterface::FEEDBACK_PUSH_URL;
    }

    /**
     * {@inheritDoc}
     */
    public function getPort()
    {
        return $this->sandboxMode
            ? ConnectionInterface::FEEDBACK_SANDBOX_PUSH_PORT
            : ConnectionInterface::FEEDBACK_PUSH_PORT;
    }
}
