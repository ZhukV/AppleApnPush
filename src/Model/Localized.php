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
 * Value object for store localized information
 */
class Localized
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var array
     */
    protected $args;

    /**
     * Constructor.
     *
     * @param string $key
     * @param array  $args
     */
    public function __construct(string $key, array $args = [])
    {
        $this->key = $key;
        $this->args = $args;
    }

    /**
     * Get localized key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get localized args
     *
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}
