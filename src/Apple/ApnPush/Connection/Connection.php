<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Connection;

use RequestStream\Stream\Socket\SocketClient;
use RequestStream\Stream\Context;
use Apple\ApnPush\Exceptions\CertificateFileNotFoundException;

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
    public function __construct($certificateFile = NULL, $certificatePassPhrase = NULL, $sandbox = FALSE)
    {
        parent::__construct($certificateFile, $certificatePassPhrase, $sandbox);
        $this->socketConnection = new SocketClient;
    }

    /**
     * {@inheritDoc}
     */
    public function createConnection()
    {
        if ($this->socketConnection->is(FALSE)) {
            return $this;
        }

        if (!$this->certificateFile) {
            throw new CertificateFileNotFoundException('Not found certificate file. Please set certificate file to connection.');
        }

        $context = new Context;
        $context->setOptions('ssl', 'local_cert', $this->certificateFile);

        if ($this->certificatePassPhrase) {
            $context->setOptions('ssl', 'passphrase', $this->certificatePassPhrase);
        }

        $this->socketConnection
            ->setTransport('ssl')
            ->setTarget($this->getConnectionUrl())
            ->setPort($this->getConnectionPort())
            ->setContext($context);

        $this->socketConnection->create();

        $this->socketConnection->setBlocking(0);
    }

    /**
     * {@inheritDoc}
     */
    public function isConnection()
    {
        return $this->socketConnection->is(FALSE);
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
    public function write($binaryData, $length = NULL)
    {
        return $this->socketConnection->write($binaryData, $length);
    }

    /**
     * {@inheritDoc}
     */
    public function isReadyRead()
    {
        return $this->socketConnection->selectRead();
    }

    /**
     * {@inheritDoc}
     */
    public function read($length)
    {
        return $this->socketConnection->read($length);
    }
}