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

use RequestStream\Stream\Socket\SocketClient;

/**
 * Default connection for Apple push notification
 */
class Connection extends AbstractConnection
{
    /**
     * @var SocketClient
     */
    protected $socketConnection;

    /**
     * {@inheritDoc}
     */
    public function __construct($certificateFile = null, $certificatePassPhrase = null, $sandbox = false)
    {
        parent::__construct($certificateFile, $certificatePassPhrase, $sandbox);
        $this->socketConnection = new SocketClient;
    }

    /**
     * {@inheritDoc}
     */
    public function createConnection()
    {
        $this->initConnection();

        $this->socketConnection->create();

        $this->socketConnection->setBlocking(0);
    }

    /**
     * {@inheritDoc}
     */
    public function isConnection()
    {
        return $this->socketConnection->is(false);
    }

    /**
     * {@inheritDoc}
     */
    public function closeConnection()
    {
        $this->socketConnection->close();
    }

    /**
     * {@inheritDoc}
     */
    public function write($binaryData, $length = null)
    {
        return $this->socketConnection->write($binaryData, $length);
    }

    /**
     * {@inheritDoc}
     */
    public function isReadyRead()
    {
        list ($second, $milisecond) = $this->readTime;
        return $this->socketConnection->selectRead($second, $milisecond);
    }

    /**
     * {@inheritDoc}
     */
    public function read($length)
    {
        return $this->socketConnection->read($length);
    }
}