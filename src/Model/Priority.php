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
 * Message priority
 */
class Priority
{
    /**
     * @var int
     */
    private $value;

    /**
     * Create immediately priority
     *
     * @return Priority
     */
    public static function immediately() : Priority
    {
        return new self(10);
    }

    /**
     * Create priority with power considerations
     *
     * @return Priority
     */
    public static function powerConsiderations() : Priority
    {
        return new self(5);
    }

    /**
     * Create new priority instance via null
     *
     * @return Priority
     */
    public static function fromNull()
    {
        return new self(0);
    }

    /**
     * Is null object?
     *
     * @return bool
     */
    public function isNull()
    {
        return $this->value === 0;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue() : int
    {
        return $this->value;
    }

    /**
     * Constructor.
     *
     * @param int $priority
     */
    private function __construct(int $priority)
    {
        $this->value = $priority;
    }
}
