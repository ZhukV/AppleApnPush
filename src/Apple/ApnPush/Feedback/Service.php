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
use Apple\ApnPush\Exceptions;
use Psr\Log\LoggerInterface;

/**
 * Feedback Service core
 */
class Service implements ServiceInterface
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
     */
    public function __construct(ConnectionInterface $connection = null)
    {
        $this->connection = $connection;
    }

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
    public function getInvalidDevices()
    {
        if (!$this->connection) {
            throw new Exceptions\ConnectionUndefinedException();
        }

        if (!$this->connection->isConnection()) {
            if ($this->logger) {
                $this->logger->debug('Create feedback connection...');
            }

            $this->connection->createConnection();
        }

        $data = $this->connection->read(-1);
        
        $this->connection->closeConnection();
        
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
     * @{inerhitDoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
