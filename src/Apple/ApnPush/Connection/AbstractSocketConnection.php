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

use RequestStream\Stream\Context;
use RequestStream\Stream\Socket\SocketClient;
use Apple\ApnPush\Exception\CertificateFileNotFoundException;

/**
 * Abstract socket connection for Apple push notification
 *
 * @author Ryan Martinsen <ryan@ryanware.com>
 */
abstract class AbstractSocketConnection extends AbstractConnection
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
     * Initialize connection
     */
    protected function init()
    {
        if ($this->socketConnection->is(false)) {
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
            ->setTarget($this->getUrl())
            ->setPort($this->getPort())
            ->setContext($context);
    }

    /**
     * {@inheritDoc}
     */
    public function is()
    {
        return $this->socketConnection->is(false);
    }

    /**
     * {@inheritDoc}
     */
    public function close()
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
