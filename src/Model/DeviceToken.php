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

class DeviceToken
{
    private string $value;

    public function __construct(string $token)
    {
        if (!\preg_match('/^[0-9a-fA-F]+$/', $token)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid device token "%s".',
                $token
            ));
        }

        $this->value = $token;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
