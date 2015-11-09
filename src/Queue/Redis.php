<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Queue;

use Apple\ApnPush\Notification\NotificationInterface;
use Apple\ApnPush\Queue\Adapter\RedisAdapter;

/**
 * Redis queue
 */
class Redis extends Queue
{
    /**
     * Create new instance from options
     *
     * @param NotificationInterface $notification
     * @param array                 $options
     *
     * @return Redis
     */
    public static function create(NotificationInterface $notification, array $options = array())
    {
        $options += array(
            'host' => '127.0.0.1',
            'port' => 6379,
            'timeout' => 0.0,
            'list_key' => 'apn.push.queue',
            'sleep_timeout' => 250000
        );

        $redis = new \Redis();
        $redis->connect($options['host'], $options['port'], $options['timeout']);

        $adapter = new RedisAdapter();
        $adapter
            ->setSleepTimeout($options['sleep_timeout'])
            ->setListKey($options['list_key'])
            ->setRedis($redis);

        return new static($adapter, $notification);
    }
}
