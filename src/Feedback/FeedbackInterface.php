<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Feedback;

use Apple\ApnPush\Connection\ConnectionInterface;
use Psr\Log\LoggerInterface;

/**
 * Interface for feedback service system
 *
 * @author Ryan Martinsen <ryan@ryanware.com>
 */
interface FeedbackInterface
{
    /**
     * Set connection
     *
     * @param ConnectionInterface $connection
     */
    public function setConnection(ConnectionInterface $connection);

    /**
     * Get connection
     *
     * @return ConnectionInterface
     */
    public function getConnection();

    /**
     * Get invalided device tokens from Apple feedback service
     *
     * @return array|Device[]
     */
    public function getInvalidDevices();

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger = null);

    /**
     * Get logger
     *
     * @return LoggerInterface
     */
    public function getLogger();
}
