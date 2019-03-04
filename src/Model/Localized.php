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
class Localized implements LocalizedInterface
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
     * Set the key
     *
     * @param string $key
     *
     * @return Localized
     */
    public function withKey(string $key) : Localized
    {
        $cloned = clone $this;

        $cloned->key = $key;

        return $cloned;
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
     * Set the args
     *
     * @param array $args
     *
     * @return Localized
     */
    public function withArgs(array $args) : Localized
    {
        $cloned = clone $this;

        $cloned->args = $args;

        return $cloned;
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
