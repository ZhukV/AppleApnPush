<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Connection;

use Apple\ApnPush\Exceptions\FeedbackException;

/**
 * Connection for the Apple Push Notification Feedback Service
 */
class Feedback extends AbstractSocketConnection
{
    /**
     * {@inheritDoc}
     */
    public function createConnection()
    {
        $this->initConnection();

        $this->socketConnection->setFlag(STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT);

        $this->socketConnection->create();
    }

    /**
     * {@inheritDoc}
     */
    public function write($binaryData, $length = null)
    {
        throw new FeedbackException('Cannot write to a feedback connection.');
    }

    /**
     * {@inheritDoc}
     */
    public function isReadyRead()
    {
        throw new FeedbackException('isReadyRead() is not used for feedback connections.');
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionUrl()
    {
        return $this->sandboxMode
            ? ConnectionInterface::FEEDBACK_SANDBOX_PUSH_URL
            : ConnectionInterface::FEEDBACK_PUSH_URL;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionPort()
    {
        return $this->sandboxMode
            ? ConnectionInterface::FEEDBACK_SANDBOX_PUSH_PORT
            : ConnectionInterface::FEEDBACK_PUSH_PORT;
    }
}
