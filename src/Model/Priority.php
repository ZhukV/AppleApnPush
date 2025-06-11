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

class Priority
{
    private int $value;

    public function __construct(int $priority)
    {
        if (!\in_array($priority, [5, 10], true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid priority "%d". Can be 5 or 10.',
                $priority
            ));
        }

        $this->value = $priority;
    }

    public static function immediately(): self
    {
        return new self(10);
    }

    public static function powerConsiderations(): self
    {
        return new self(5);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
