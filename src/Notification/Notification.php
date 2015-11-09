<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Notification;

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Connection\ConnectionInterface;
use Apple\ApnPush\Exception;
use Apple\ApnPush\Notification\Events\SendMessageCompleteEvent;
use Apple\ApnPush\Notification\Events\SendMessageErrorEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var bool
     */
    protected $checkForErrors = true;

    /**
     * Recreate connection if apn server returned empty data
     *
     * @var bool
     */
    protected $recreateConnection = true;

    /**
     * Construct
     *
     * @param string|ConnectionInterface $connection
     * @param PayloadFactoryInterface    $payloadFactory
     */
    public function __construct($connection = null, PayloadFactoryInterface $payloadFactory = null)
    {
        if (null !== $connection) {
            if ($connection instanceof ConnectionInterface) {
                $this->connection = $connection;
            } elseif (is_string($connection)) {
                // Connection is a certificate path file
                $certificate = new Certificate($connection, null);
                $this->connection = new Connection($certificate);
            }
        }

        $this->payloadFactory = $payloadFactory ?: new PayloadFactory();
    }

    /**
     * Set connection to manager
     *
     * @param ConnectionInterface $connection
     *
     * @return Notification
     */
    public function setConnection(ConnectionInterface $connection)
    {
        if ($this->connection) {
            // Close old connection
            $this->connection->close();
        }

        $this->connection = $connection;

        return $this;
    }

    /**
     * Get connection
     *
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set payload factory for generate apn data
     *
     * @param PayloadFactoryInterface $payloadFactory
     *
     * @return Notification
     */
    public function setPayloadFactory(PayloadFactoryInterface $payloadFactory)
    {
        $this->payloadFactory = $payloadFactory;

        return $this;
    }

    /**
     * Get payload factory
     *
     * @return PayloadFactoryInterface
     */
    public function getPayloadFactory()
    {
        return $this->payloadFactory;
    }

    /**
     * Set logger for logging all actions
     *
     * @param LoggerInterface $logger
     *
     * @return Notification
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Get logger
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Set event dispatcher
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return Notification
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    /**
     * Get event dispatcher
     *
     * @return EventDispatcherInterface|null
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * Set message to iOS devices
     *
     * @param MessageInterface $message
     *
     * @return bool
     *
     * @throws SendException
     * @throws Exception\PayloadFactoryUndefinedException
     * @throws Exception\ConnectionUndefinedException
     * @throws Exception\DeviceTokenNotFoundException
     */
    public function send(MessageInterface $message)
    {
        if (!$this->payloadFactory) {
            throw new Exception\PayloadFactoryUndefinedException();
        }

        if (!$this->connection) {
            throw new Exception\ConnectionUndefinedException();
        }

        if (!$message->getDeviceToken()) {
            throw new Exception\DeviceTokenNotFoundException();
        }

        if (!$this->connection->is()) {
            if ($this->logger) {
                $this->logger->debug('Connect...');
            }

            $this->connection->connect();
        }

        try {
            // Send payload data to apns server
            $response = $this->sendPayload($message);
        } catch (SendException $error) {
            if ($this->eventDispatcher) {
                // Call to event: Error send message
                $event = new SendMessageErrorEvent($message, $error);
                $this->eventDispatcher->dispatch(NotificationEvents::SEND_MESSAGE_ERROR, $event);
            }

            if ($this->logger) {
                // Write error to log
                $this->logger->error((string) $error);
                $this->logger->debug('Close connection...');
            }

            $this->connection->close();

            throw $error;
        }

        if ($this->eventDispatcher) {
            // Call to event: Complete send message
            $event = new SendMessageCompleteEvent($message);
            $this->eventDispatcher->dispatch(NotificationEvents::SEND_MESSAGE_COMPLETE, $event);
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
     * Send message with parameters
     *
     * @param string  $deviceToken    Device token (/^[a-z0-9]{64}$/i)
     * @param string  $body           Message content
     * @param integer $messIdentifier Message identifier
     * @param integer $badge          Badge
     * @param string  $sound          Path to sound file in application or sound key
     *
     * @return bool
     */
    public function sendMessage($deviceToken, $body, $messIdentifier = null, $badge = null, $sound = null)
    {
        $message = $this->createMessage();
        $message->setDeviceToken($deviceToken);
        $message->setBody($body);
        $message->setIdentifier($messIdentifier);
        $message->setBadge($badge);
        $message->setSound($sound);

        return $this->send($message);
    }

    /**
     * Create new message
     *
     * @return Message
     */
    public function createMessage()
    {
        return new Message();
    }

    /**
     * Set status for require check errors
     *
     * @param bool $check
     *
     * @return Notification
     */
    public function setCheckForErrors($check)
    {
        $this->checkForErrors = $check;

        return $this;
    }

    /**
     * Is check for errors
     *
     * @return bool
     */
    public function isCheckForErrors()
    {
        return $this->checkForErrors;
    }

    /**
     * Set try recreate connection if apn server returned empty data
     *
     * @param bool $recreateConnection
     *
     * @return Notification
     */
    public function setRecreateConnection($recreateConnection)
    {
        $this->recreateConnection = (bool) $recreateConnection;

        return $this;
    }

    /**
     * Is try recreate connection if apn server returned empty data
     *
     * @return bool
     */
    public function isRecreateConnection()
    {
        return $this->recreateConnection;
    }

    /**
     * Send payload data to apple apns server
     *
     * @param MessageInterface $message
     *
     * @return bool
     *
     * @throws SendException
     */
    private function sendPayload(MessageInterface $message)
    {
        $payload = $this->payloadFactory->createPayload($message);

        $response = $this->writePayload($payload);

        if ($this->checkForErrors && $this->connection->isReadyRead()) {
            $responseApn = $this->connection->read(6);

            if (!$responseApn && $this->recreateConnection) {
                // Not read data from apn server. Close connection?
                if ($this->logger) {
                    $this->logger->debug(
                        'APN server returned empty data. Close connection? Try recreate connection...'
                    );
                }

                // Try recreate connection
                $this->connection->close();
                $this->connection->connect();

                $response = $this->writePayload($payload);

                if ($this->connection->isReadyRead()) {
                    $responseApn = $this->connection->read(6);
                } else {
                    return $response;
                }
            }

            $error = SendException::parseFromAppleResponse($responseApn, $message);

            throw $error;
        }

        return $response;
    }

    /**
     * Write payload data to connection
     *
     * @param string $payload
     *
     * @return bool
     */
    private function writePayload($payload)
    {
        return strlen($payload) === $this->connection->write($payload, strlen($payload));
    }
}
