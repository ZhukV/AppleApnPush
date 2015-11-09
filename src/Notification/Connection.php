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

use Apple\ApnPush\Connection\Connection as BaseConnection;

/**
 * Default connection for Apple push notification
 */
class Connection extends BaseConnection
{
    /**
     * Initialize connection
     *
     * @return Connection
     */
    public function connect()
    {
        $this->socketClientFlags = STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT;

        parent::connect();

        stream_set_blocking($this->resource, 0);

        return $this;
    }
}
