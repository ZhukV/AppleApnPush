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

use Apple\ApnPush\Messages\MessageInterface;
use Apple\ApnPush\Exceptions\ApnPushException;

/**
 * Control error with send message
 */
class SendException extends ApnPushException implements SendExceptionInterface
{
    /**
     * @var array
     */
    static protected $errorMessages = array(
        SendExceptionInterface::NO_ERRORS                       =>  'Not errors',
        SendExceptionInterface::ERROR_PROCESSING                =>  'Processing error',
        SendExceptionInterface::ERROR_MISSING_DEVICE_TOKEN      =>  'Missing device token',
        SendExceptionInterface::ERROR_MISSING_TOPIC             =>  'Missing topic',
        SendExceptionInterface::ERROR_MISSING_PAYLOAD           =>  'Missing payload',
        SendExceptionInterface::ERROR_INVALID_TOKEN_SIZE        =>  'Invalid token size',
        SendExceptionInterface::ERROR_INVALID_TOPIC_SIZE        =>  'Invalid topic size',
        SendExceptionInterface::ERROR_INVALID_PAYLOAD_SIZE      =>  'Invalid payload size',
        SendExceptionInterface::ERROR_INVALID_TOKEN             =>  'Invalid token',
        SendExceptionInterface::ERROR_UNKNOWN                   =>  'Unknown error',
        SendExceptionInterface::ERROR_UNPACK_RESPONSE           =>  'Unpack response error'
    );

    /**
     * @var integer
     */
    protected $statusCode;

    /**
     * @var integer
     */
    protected $command;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var MessageInterface $message
     */
    protected $messageObject;

    /**
     * Construct
     *
     * @param integer $statusCode
     * @param integer $command
     * @param string $identifier
     * @param MessageInterface $message
     */
    public function __construct($statusCode, $command, $identifier, MessageInterface $message = null)
    {
        if (isset(self::$errorMessages[$statusCode])) {
            $messageStr = self::$errorMessages[$statusCode];
        }
        else {
            $messageStr = 'Undefined error with status: "' . $statusCode. '".';
        }

        parent::__construct($messageStr, $statusCode);

        $this->messageObject = $message;
        $this->statusCode = $statusCode;
        $this->command = $command;
        $this->identifier = $identifier;
    }

    /**
     * {@inheritDoc}
     */
    public static function parseFromAppleResponse($binaryData, MessageInterface $message = null)
    {
        if(false === $response = @unpack("Ccommand/Cstatus/Nidentifier", $binaryData)) {
            return new static(SendExceptionInterface::ERROR_UNPACK_RESPONSE, 0, 0, $message);
        }

        return new static(
            $response['status'],
            $response['command'],
            $response['identifier'],
            $message
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * {@inheritDoc}
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessageObject()
    {
        return $this->messageObject;
    }

    /**
     * __toString
     */
    public function __toString()
    {
        return sprintf(
            'Error sending push: "%s", with code status: %d. Message identifier: %d.%s',
            $this->getMessage(),
            $this->statusCode,
            $this->identifier,
            ($this->messageObject ? ' Device token: ' . $this->messageObject->getDeviceToken() : '')
        );
    }
}