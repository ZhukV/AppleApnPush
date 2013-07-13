<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Feedback;

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
     * @param Connection
     */
    public function __construct($connection = null)
    {
        if (null !== $connection) {
            if ($connection instanceof ConnectionInterface) {
                $this->connection = $connection;
            } else if (is_string($connection)) {
                // Connection is a certificate path file
                $this->connection = new Connection($connection);
            }
        }
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
    public function getInvalidDevices()
    {
        if (!$this->connection) {
            throw new Exception\ConnectionUndefinedException();
        }

        if (!$this->connection->is()) {
            if ($this->logger) {
                $this->logger->debug('Create feedback connection...');
            }

            $this->connection->create();
        }

        $data = $this->connection->read(-1);
        
        $this->connection->close();
        
        $feedback = array();
        if (!empty($data)) {
            foreach (str_split($data, 38) as $device_data) {
                $feedback[] = new Device($device_data);
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
}
