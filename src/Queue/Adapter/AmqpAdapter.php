<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Queue\Adapter;

use Apple\ApnPush\Notification\MessageInterface;

/**
 * AMQP Adapter
 */
class AmqpAdapter implements AdapterInterface
{
    /**
     * @var \AMQPQueue
     */
    private $queue;

    /**
     * @var \AMQPExchange
     */
    private $exchange;

    /**
     * @var string
     */
    private $routingKey;

    /**
     * @var integer
     */
    private $publishFlag = AMQP_NOPARAM;

    /**
     * @var array
     */
    private $publishOptions = array();

    /**
     * Set amqp queue
     *
     * @param \AMQPQueue $queue
     *
     * @return AmqpAdapter
     */
    public function setQueue(\AMQPQueue $queue = null)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Get queue
     *
     * @return \AMQPQueue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set exchange
     *
     * @param \AMQPExchange $exchange
     *
     * @return AmqpAdapter
     */
    public function setExchange(\AMQPExchange $exchange = null)
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * Get exchange
     *
     * @return \AMQPExchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * Set routingKey
     *
     * @param string $routingKey
     *
     * @return AmqpAdapter
     */
    public function setRoutingKey($routingKey)
    {
        $this->routingKey = $routingKey;

        return $this;
    }

    /**
     * Get routingKey
     *
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * Set publishFlag
     *
     * @param int $publishFlag
     *
     * @return AmqpAdapter
     */
    public function setPublishFlag($publishFlag)
    {
        $this->publishFlag = $publishFlag;

        return $this;
    }

    /**
     * Get publishFlag
     *
     * @return int
     */
    public function getPublishFlag()
    {
        return $this->publishFlag;
    }

    /**
     * Set publishOptions
     *
     * @param array $publishOptions
     *
     * @return AmqpAdapter
     */
    public function setPublishOptions($publishOptions)
    {
        $this->publishOptions = $publishOptions;

        return $this;
    }

    /**
     * Get publishOptions
     *
     * @return array
     */
    public function getPublishOptions()
    {
        return $this->publishOptions;
    }

    /**
     * Is adapter can receive message
     *
     * @return bool
     */
    public function isNextReceive()
    {
        return true;
    }

    /**
     * Get message from queue
     *
     * @return \Apple\ApnPush\Notification\MessageInterface|null
     *
     * @throws \RuntimeException
     */
    public function getMessage()
    {
        if (null === $this->queue) {
            throw new \RuntimeException('Can\'n get message. Not found queue.');
        }

        $message = null;

        $this->queue->consume(function (\AMQPEnvelope $amqpMessage, \AMQPQueue $queue) use (&$message) {
            $message = unserialize($amqpMessage->getBody());

            $queue->ack($amqpMessage->getDeliveryTag());

            // End consume process
            return false;
        }, AMQP_NOPARAM);

        return $message;
    }

    /**
     * Add message to queue
     *
     * @param MessageInterface $message
     *
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function addMessage(MessageInterface $message)
    {
        if (null === $this->routingKey) {
            throw new \RuntimeException('Can\'t send message. Publish routing key is undefined.');
        }

        if (null === $this->exchange) {
            throw new \RuntimeException('Can\'t send message. Exchange not found.');
        }

        return $this->exchange->publish(
            serialize($message),
            $this->routingKey,
            $this->publishFlag,
            $this->publishOptions
        );
    }
}
