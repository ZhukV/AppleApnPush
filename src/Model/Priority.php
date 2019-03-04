<?php

declare(strict_types = 1);

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
class Priority implements PriorityInterface
{
    /**
     * @var int
     */
    private $value;

    /**
     * Constructor.
     *
     * @param int $value
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $value)
    {
        $this->validateValue($value);

        $this->value = $value;
    }

    /**
     * Create immediately priority
     *
     * @return Priority
     */
    public static function immediately(): Priority
    {
        return new self(10);
    }

    /**
     * Create priority with power considerations
     *
     * @return Priority
     */
    public static function powerConsiderations(): Priority
    {
        return new self(5);
    }

    /**
     * Set the value
     *
     * @param int $value
     *
     * @return Priority
     *
     * @throws \InvalidArgumentException
     */
    public function withValue(int $value) : Priority
    {
        $this->validateValue($value);

        $cloned = clone $this;

        $cloned->value = $value;

        return $cloned;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Determines if value is valid, throws exception if not
     *
     * @param int $value
     *
     * @throws \InvalidArgumentException
     */
    private function validateValue(int $value) : void
    {
        if (!in_array($value, [5, 10], true)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid priority "%d". Can be 5 or 10.',
                $value
            ));
        }
    }
}
