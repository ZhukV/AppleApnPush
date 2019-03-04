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
 * The value object for store the apns-collapse-id
 */
class CollapseId implements CollapseIdInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * Constructor.
     *
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $value)
    {
        $this->validateValue($value);

        $this->value = $value;
    }

    /**
     * Set the value
     *
     * @param string $value
     *
     * @return CollapseId
     *
     * @throws \InvalidArgumentException
     */
    public function withValue(string $value) : CollapseId
    {
        $this->validateValue($value);

        $cloned = clone $this;

        $cloned->value = $value;

        return $cloned;
    }

    /**
     * Get the value of collapse id
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Determines if value is valid, throws exception if not
     *
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    private function validateValue(string $value) : void
    {
        if (strlen($value) > 64) {
            throw new \InvalidArgumentException('The apns-collapse-id cannot be larger than 64 bytes.');
        }
    }
}
