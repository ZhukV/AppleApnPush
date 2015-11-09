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

use Apple\ApnPush\Exception\ApnPushException;

/**
 * Control error with send message
 */
class SendException extends ApnPushException
{
    const NO_ERRORS                     =   0;
    const ERROR_PROCESSING              =   1;
    const ERROR_MISSING_DEVICE_TOKEN    =   2;
    const ERROR_MISSING_TOPIC           =   3;
    const ERROR_MISSING_PAYLOAD         =   4;
    const ERROR_INVALID_TOKEN_SIZE      =   5;
    const ERROR_INVALID_TOPIC_SIZE      =   6;
    const ERROR_INVALID_PAYLOAD_SIZE    =   7;
    const ERROR_INVALID_TOKEN           =   8;
    const ERROR_UNKNOWN                 =   255;
    const ERROR_UNPACK_RESPONSE         =   256;

    /**
     * @var array
     */
    protected static $errorMessages = array(
        self::NO_ERRORS                       =>  'Not errors',
        self::ERROR_PROCESSING                =>  'Processing error',
        self::ERROR_MISSING_DEVICE_TOKEN      =>  'Missing device token',
        self::ERROR_MISSING_TOPIC             =>  'Missing topic',
        self::ERROR_MISSING_PAYLOAD           =>  'Missing payload',
        self::ERROR_INVALID_TOKEN_SIZE        =>  'Invalid token size',
        self::ERROR_INVALID_TOPIC_SIZE        =>  'Invalid topic size',
        self::ERROR_INVALID_PAYLOAD_SIZE      =>  'Invalid payload size',
        self::ERROR_INVALID_TOKEN             =>  'Invalid token',
        self::ERROR_UNKNOWN                   =>  'Unknown error',
        self::ERROR_UNPACK_RESPONSE           =>  'Unpack response error'
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
     * @param integer          $statusCode
     * @param integer          $command
     * @param string           $identifier
     * @param MessageInterface $message
     */
    public function __construct($statusCode, $command, $identifier, MessageInterface $message = null)
    {
        if (isset(self::$errorMessages[$statusCode])) {
            $messageStr = self::$errorMessages[$statusCode];
        } else {
            $messageStr = 'Undefined error with status: "' . $statusCode. '".';
        }

        parent::__construct($messageStr, $statusCode);

        $this->messageObject = $message;
        $this->statusCode = $statusCode;
        $this->command = $command;
        $this->identifier = $identifier;
    }

    /**
     * Parse exception from apple response
     *
     * @param string           $binaryData
     * @param MessageInterface $message
     *
     * @return SendException
     */
    public static function parseFromAppleResponse($binaryData, MessageInterface $message = null)
    {
        $unpackError = false;

        // Register custom error handler for control unpack error
        set_error_handler(function () use (&$unpackError) {
            $unpackError = true;
        });

        // Unpack response
        $response = unpack("Ccommand/Cstatus/Nidentifier", $binaryData);

        // Restore custom error handler
        restore_error_handler();

        if ($unpackError) {
            return new static(self::ERROR_UNPACK_RESPONSE, 0, 0, $message);
        }

        return new static(
            $response['status'],
            $response['command'],
            $response['identifier'],
            $message
        );
    }

    /**
     * Get status code
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Get command
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Get message object
     *
     * @return MessageInterface
     */
    public function getMessageObject()
    {
        return $this->messageObject;
    }

    /**
     * __toString
     *
     * @return string
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
