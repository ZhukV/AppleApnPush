<?php

declare(strict_types=1);

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
     * Constructor.
     *
     * @param int $priority
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $priority)
    {
        if (!in_array($priority, [5, 10], true)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid priority "%d". Can be 5 or 10.',
                $priority
            ));
        }

        $this->value = $priority;
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
     * Get value
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
