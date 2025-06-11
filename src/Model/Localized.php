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

class Localized
{
    protected string $key;
    protected array $args;

    public function __construct(string $key, array $args = [])
    {
        $this->key = $key;
        $this->args = $args;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
