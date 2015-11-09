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
 * Redis queue adapter
 */
class RedisAdapter implements AdapterInterface
{
    /**
     * Sleep timeout in micro seconds
     *
     * @var integer
     */
    private $sleepTimeout = 250000;

    /**
     * @var string
     */
    private $listKey;

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * Set sleepTimeout
     *
     * @param int $sleepTimeout
     *
     * @return RedisAdapter
     */
    public function setSleepTimeout($sleepTimeout)
    {
        $this->sleepTimeout = $sleepTimeout;

        return $this;
    }

    /**
     * Get sleepTimeout
     *
     * @return int
     */
    public function getSleepTimeout()
    {
        return $this->sleepTimeout;
    }

    /**
     * Set listKey
     *
     * @param string $listKey
     *
     * @return RedisAdapter
     */
    public function setListKey($listKey)
    {
        $this->listKey = $listKey;

        return $this;
    }

    /**
     * Get listKey
     *
     * @return string
     */
    public function getListKey()
    {
        return $this->listKey;
    }

    /**
     * Set redis
     *
     * @param \Redis $redis
     *
     * @return RedisAdapter
     */
    public function setRedis(\Redis $redis = null)
    {
        $this->redis = $redis;

        return $this;
    }

    /**
     * Get redis
     *
     * @return \Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * Is adapter can receive next message
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
        static $useSleep = false;

        if (!$this->listKey) {
            throw new \RuntimeException('Can\'t get message. Undefined list key.');
        }

        if (!$this->redis) {
            throw new \RuntimeException('Can\'t get message. Not found redis instance.');
        }

        if ($useSleep && $this->sleepTimeout) {
            usleep($this->sleepTimeout);
        }

        if ($data = $this->redis->lPop($this->listKey)) {
            $useSleep = false;

            return unserialize($data);
        } else {
            $useSleep = true;
        }

        return null;
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
        if (!$this->listKey) {
            throw new \RuntimeException('Can\'t send message. Undefined list key.');
        }

        if (null === $this->redis) {
            throw new \RuntimeException('Can\'t send message. Not found redis instance.');
        }

        return $this->redis->rPush($this->listKey, serialize($message));
    }
}
