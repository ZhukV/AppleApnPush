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

use Apple\ApnPush\Certificate\CertificateInterface;
use Apple\ApnPush\Exception\CertificateFileNotFoundException;
use Apple\ApnPush\Exception\SocketErrorException;

/**
 * Connection for Apple push notification
 */
abstract class Connection implements ConnectionInterface
{
    /**
     * @var CertificateInterface
     */
    protected $certificate;

    /**
     * @var bool
     */
    protected $sandboxMode;

    /**
     * @var array
     */
    protected $readTime = array(1, 0);

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var int
     */
    protected $socketClientFlags = STREAM_CLIENT_CONNECT;

    /**
     * Construct
     *
     * @param CertificateInterface $certificate
     * @param bool                 $sandboxMode
     */
    public function __construct(CertificateInterface $certificate, $sandboxMode = false)
    {
        $this->certificate = $certificate;
        $this->sandboxMode = $sandboxMode;
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
        // Close connection
        $this->close();
    }

    /**
     * Set the read time limit
     *
     * @param int $second
     * @param int $uSecond
     *
     * @return Connection
     *
     * @throws \InvalidArgumentException
     */
    public function setReadTime($second, $uSecond = 0)
    {
        if (!is_integer($second)) {
            throw new \InvalidArgumentException(sprintf(
                'Second must be a integer value, "%s" given.',
                gettype($second)
            ));
        }

        if (!is_integer($uSecond)) {
            throw new \InvalidArgumentException(sprintf(
                'Millisecond must be a integer value, "%s" given.',
                gettype($uSecond)
            ));
        }

        $this->readTime = array($second, $uSecond);

        return $this;
    }

    /**
     * Get read time limit
     *
     * @return array
     */
    public function getReadTime()
    {
        return $this->readTime;
    }

    /**
     * Get status use sandbox mode
     *
     * @return bool
     */
    public function isSandboxMode()
    {
        return $this->sandboxMode;
    }

    /**
     * Get url for connection
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->sandboxMode
            ? ConnectionInterface::GATEWAY_SANDBOX_PUSH_URL
            : ConnectionInterface::GATEWAY_PUSH_URL;
    }

    /**
     * Get port for connection
     *
     * @return integer
     */
    public function getPort()
    {
        return $this->sandboxMode
            ? ConnectionInterface::GATEWAY_SANDBOX_PUSH_PORT
            : ConnectionInterface::GATEWAY_PUSH_PORT;
    }

    /**
     * Is connection active
     *
     * @return bool
     */
    public function is()
    {
        return (bool)$this->resource;
    }

    /**
     * Create connection
     *
     * @return Connection
     *
     * @throws CertificateFileNotFoundException
     * @throws SocketErrorException
     */
    public function connect()
    {
        if ($this->resource) {
            // Connection already created
            return $this;
        }

        $context = stream_context_create(array(
            'ssl' => array(
                'local_cert' => $this->certificate->getPath(),
                'passphrase' => $this->certificate->getPassPhrase(),
            ),
        ));

        $errorMessage = null;

        // Register custom error handler for control technical error
        // @codeCoverageIgnoreStart
        set_error_handler(function ($errCode, $errStr) use (&$errorMessage) {
            if (!$errorMessage) {
                $parts = explode(':', $errStr, 2);
                $errorMessage = isset($parts[1]) ? trim($parts[1]) : 'Undefined';
            }
        });
        // @codeCoverageIgnoreEnd

        $resource = stream_socket_client(
            'ssl://' . $this->getUrl() . ':' . $this->getPort(),
            $errorCode,
            $errorStr,
            ini_get('default_socket_timeout'),
            $this->socketClientFlags,
            $context
        );

        // Restore custom error handler
        restore_error_handler();

        if (!$resource) {
            // Error with create socket
            if (!$errorCode && !$errorStr) {
                // Error in create socket client (Can context)
                if (!$errorMessage) {
                    $errorMessage = 'Socket client not created. Technical error in system.';
                }

                throw new SocketErrorException($errorMessage, $errorCode ?: 0);
            } else {
                throw new SocketErrorException($errorCode . ': ' . $errorStr, $errorCode);
            }
        }

        $this->resource = $resource;

        return $this;
    }

    /**
     * Write data to connection
     *
     * @param string  $data
     * @param integer $length
     *
     * @return int
     *
     * @throws SocketErrorException
     */
    public function write($data, $length)
    {
        if (!$this->resource) {
            throw new SocketErrorException('Can\'t write to socket. Socket not created.');
        }

        return fwrite($this->resource, $data, $length);
    }

    /**
     * Read data from connection
     *
     * @param int $length
     *
     * @return string
     *
     * @throws SocketErrorException
     */
    public function read($length)
    {
        if (!$this->resource) {
            throw new SocketErrorException('Can\'t read from socket. Socket not created.');
        }

        return stream_get_contents($this->resource, $length, -1);
    }

    /**
     * Is ready read
     *
     * @throws SocketErrorException
     *
     * @return bool
     */
    public function isReadyRead()
    {
        if (!$this->resource) {
            throw new SocketErrorException('Can\'t check ready read. Socket not created.');
        }

        $selectRead = array($this->resource);
        $null = null;

        list ($seconds, $uSeconds) = $this->readTime;

        return (bool)stream_select($selectRead, $null, $null, $seconds, $uSeconds);
    }

    /**
     * Close connection
     *
     * @return Connection
     */
    public function close()
    {
        if ($this->resource) {
            stream_socket_shutdown($this->resource, STREAM_SHUT_RDWR);
            unset ($this->resource);
            $this->resource = null;
        }

        return $this;
    }
}
