<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

use Apple\ApnPush\Connection\ConnectionInterface,
    Apple\ApnPush\Messages\MessageInterface,
    Apple\ApnPush\PayloadFactory\PayloadFactoryInterface,
    Apple\ApnPush\Exceptions,
    Apple\ApnPush\Feedback\FeedbackException;

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
     * @{inerhitDoc}
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @{inerhitDoc}
     */
    public function setPayloadFactory(PayloadFactoryInterface $payloadFactory)
    {
        $this->payloadFactory = $payloadFactory;

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getPayloadFactory()
    {
        return $this->payloadFactory;
    }

    /**
     * @{inerhitDoc}
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

        if (!$this->connection->isConnection()) {
            $this->connection->createConnection();
        }

        $response = (mb_strlen($payload) === $this->connection->write($payload, mb_strlen($payload)));

        if ($this->connection->isReadyRead()) {
            $responseApple = $this->connection->read(6);
            throw SendException::parseFromAppleResponse($responseApple, $message);
        }

        return $response;
    }
}