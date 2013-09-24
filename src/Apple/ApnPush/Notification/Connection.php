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

use Apple\ApnPush\Connection\AbstractSocketConnection;

/**
 * Default connection for Apple push notification
 */
class Connection extends AbstractSocketConnection
{
    /**
     * Initialize connection
     */
    public function create()
    {
        $this->init();
        $this->socketConnection->create();
        $this->socketConnection->setBlocking(0);
    }
}
