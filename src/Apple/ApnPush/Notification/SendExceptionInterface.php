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

use Apple\ApnPush\Messages\MessageInterface;

/**
 * Feedback exception
 */
interface SendExceptionInterface
{
    /**
     * Error constants
     */
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
     * Parse feedback from apple response
     *
     * @param string $binaryData
     * @param MessageInterface $message
     */
    public static function parseFromAppleResponse($binaryData, MessageInterface $message = null);

    /**
     * Get status code
     *
     * @return integer
     */
    public function getStatusCode();

    /**
     * Get command
     *
     * @return integer
     */
    public function getCommand();

    /**
     * Get message
     *
     * @return Message
     */
    public function getMessage();

    /**
     * Get identifier
     *
     * @return integer
     */
    public function getIdentifier();
}