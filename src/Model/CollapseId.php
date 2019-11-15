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
class CollapseId
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
        if (\strlen($value) > 64) {
            throw new \InvalidArgumentException('The apns-collapse-id cannot be larger than 64 bytes.');
        }

        $this->value = $value;
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
}
