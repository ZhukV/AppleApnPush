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
use Apple\ApnPush\Queue\Adapter\AmqpAdapter;

/**
 * Amqp queue
 */
class Amqp extends Queue
{
    /**
     * Create new instance from options
     *
     * @param NotificationInterface $notification
     * @param array                 $options
     *
     * @return Amqp
     */
    public static function create(NotificationInterface $notification, array $options = array())
    {
        $options += array(
            // Connection options
            'host' => '127.0.0.1',
            'port' => 5672,
            'vhost' => '/',
            'login' => 'guest',
            'password' => 'guest',

            // Exchange and queue options
            'queue_name' => 'apn.push.queue',
            'publish_options' => array(),
            'publish_flag' => AMQP_NOPARAM
        );

        // Create AMQP Connection
        $amqpConnection = new \AMQPConnection(array(
            'host' => $options['host'],
            'port' => $options['port'],
            'vhost' => $options['vhost'],
            'login' => $options['login'],
            'password' => $options['password']
        ));

        $amqpConnection->connect();

        // Create AMQP Channel
        $channel = new \AMQPChannel($amqpConnection);

        // Create exchange
        $exchange = new \AMQPExchange($channel);

        // Create queue
        $queue = new \AMQPQueue($channel);
        $queue->setName($options['queue_name']);
        /** @see https://github.com/pdezwart/php-amqp/pull/58 */
        $queue->declareQueue();

        // Create amqp adapter
        $adapter = new AmqpAdapter();
        $adapter
            ->setRoutingKey($options['queue_name'])
            ->setPublishFlag($options['publish_flag'])
            ->setPublishOptions($options['publish_options'])
            ->setExchange($exchange)
            ->setQueue($queue);

        /** @var Amqp $amqp */
        $amqp = new static();

        $amqp
            ->setNotification($notification)
            ->setAdapter($adapter);

        return $amqp;
    }
}
