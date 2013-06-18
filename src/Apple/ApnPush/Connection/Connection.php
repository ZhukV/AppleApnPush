<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Connection;

/**
 * Default connection for Apple push notification
 */
class Connection extends AbstractSocketConnection
{
    /**
     * {@inheritDoc}
     */
    public function createConnection()
    {
        $this->initConnection();

        $this->socketConnection->create();

        $this->socketConnection->setBlocking(0);
    }
}
