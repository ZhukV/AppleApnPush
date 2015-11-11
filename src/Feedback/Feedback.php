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

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Connection\ConnectionInterface;
use Apple\ApnPush\Exception;
use Psr\Log\LoggerInterface;

/**
 * Feedback Service core
 *
 * @author Ryan Martinsen <ryan@ryanware.com>
 */
class Feedback implements FeedbackInterface
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Construct
     *
     * @param ConnectionInterface|string $connection
     */
    public function __construct($connection = null)
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
    }

    /**
     * Set connection
     *
     * @param ConnectionInterface $connection
     *
     * @return Feedback
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
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get invalid devices
     *
     * @return array|Device[]
     *
     * @throws Exception\ConnectionUndefinedException
     */
    public function getInvalidDevices()
    {
        if (!$this->connection) {
            throw new Exception\ConnectionUndefinedException();
        }

        if (!$this->connection->is()) {
            if ($this->logger) {
                $this->logger->debug('Create feedback connection...');
            }

            $this->connection->connect();
        }

        $data = $this->connection->read(-1);

        $this->connection->close();

        $feedback = array();

        if ($data) {
            foreach (str_split($data, 38) as $deviceData) {
                $feedback[] = new Device($deviceData);
            }
        }

        if ($this->logger) {
            $this->logger->info(sprintf(
                '%d device tokens received from feedback service.',
                count($feedback)
            ));
        }

        return $feedback;
    }

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     *
     * @return Feedback
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
}
