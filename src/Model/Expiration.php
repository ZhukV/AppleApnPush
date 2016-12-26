<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Model;

/**
 * Expiration of notification
 */
class Expiration
{
    /**
     * @var \DateTime
     */
    private $storeTo;

    /**
     * @var bool
     */
    private $null;

    /**
     * Create new expiration with store notification to time
     *
     * @param \DateTime $availableTo
     *
     * @return Expiration
     */
    public static function storeTo(\DateTime $availableTo) : Expiration
    {
        $expiration = new self();
        $expiration->storeTo = clone $availableTo;
        $expiration->storeTo->setTimezone(new \DateTimeZone('UTC'));

        return $expiration;
    }

    /**
     * Create new expiration without store notification
     *
     * @return Expiration
     */
    public static function notStore() : Expiration
    {
        return new self();
    }

    /**
     * Create expiration from null
     *
     * @return Expiration
     */
    public static function fromNull() : Expiration
    {
        $expiration = new self();
        $expiration->null = true;

        return $expiration;
    }

    /**
     * Is null object?
     *
     * @return bool
     */
    public function isNull()
    {
        return (bool) $this->null;
    }

    /**
     * Get value
     *
     * @return int
     *
     * @throws \LogicException
     */
    public function getValue() : int
    {
        if ($this->isNull()) {
            throw new \LogicException('Can not get value of expiration from null value.');
        }

        if (!$this->storeTo) {
            return 0;
        }

        return $this->storeTo->format('U');
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
    }
}
