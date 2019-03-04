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
 * UUID identifier for APN
 */
class ApnId implements ApnIdInterface
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
     * @return ApnId
     *
     * @throws \InvalidArgumentException
     */
    public function withValue(string $value) : ApnId
    {
        $this->validateValue($value);

        $cloned = clone $this;

        $cloned->value = $value;

        return $cloned;
    }

    /**
     * Get value
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
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid UUID identifier "%s".',
                $value
            ));
        }
    }
}
