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
class ApnId
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
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid UUID identifier "%s".',
                $value
            ));
        }

        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string
     *
     * @throws \LogicException
     */
    public function getValue(): string
    {
        return (string) $this->value;
    }
}
