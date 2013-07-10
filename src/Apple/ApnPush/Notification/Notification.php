<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

use Apple\ApnPush\Connection\ConnectionInterface;
use Apple\ApnPush\Messages\MessageInterface;
use Apple\ApnPush\PayloadFactory\PayloadFactoryInterface;
use Apple\ApnPush\PayloadFactory\PayloadFactory;
use Apple\ApnPush\Exceptions;
use Psr\Log\LoggerInterface;

/**
 * Notification core
 */
class Notification implements NotificationInterface
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var PayloadFactoryInterface
     */
    protected $payloadFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $checkForErrors = true;

    /**
     * Construct
     */
    public function __construct(PayloadFactoryInterface $payloadFactory = null, ConnectionInterface $connection = null)
    {
        $this->connection = $connection;
        $this->payloadFactory = $payloadFactory === null ? new PayloadFactory() : $payloadFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * {@inheritDoc}
     */
    public function setPayloadFactory(PayloadFactoryInterface $payloadFactory)
    {
        $this->payloadFactory = $payloadFactory;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPayloadFactory()
    {
        return $this->payloadFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function sendMessage(MessageInterface $message)
    {
        if (!$this->payloadFactory) {
            throw new Exceptions\PayloadFactoryUndefinedException();
        }

        if (!$this->connection) {
            throw new Exceptions\ConnectionUndefinedException();
        }

        if (!$message->getDeviceToken()) {
            throw new Exceptions\DeviceTokenNotFoundException();
        }

        $payload = $this->payloadFactory->createPayload($message);

        if ($this->logger) {
            $this->logger->debug('Success create payload.');
        }

        if (!$this->connection->isConnection()) {
            if ($this->logger) {
                $this->logger->debug('Create connection...');
            }

            $this->connection->createConnection();
        }

        $response = (mb_strlen($payload) === $this->connection->write($payload, mb_strlen($payload)));

        if ($this->checkForErrors && $this->connection->isReadyRead()) {
            $responseApple = $this->connection->read(6);
            $error = SendException::parseFromAppleResponse($responseApple, $message);

            if ($this->logger) {
                $this->logger->error((string) $error);
            }

            $this->connection->closeConnection();

            throw $error;
        }

        if ($this->logger) {
            $this->logger->info(sprintf(
                'Success send notification to device "%s" by message identifier "%s".',
                $message->getDeviceToken(),
                $message->getIdentifier()
            ));
        }

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * {@inheritDoc}
     */
    public function setCheckForErrors($check)
    {
        return $this->checkForErrors = $check;
    }
}
