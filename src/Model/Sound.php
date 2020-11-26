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
 * Value object for store sound object
 */
class Sound
{
    /**
     * @var bool
     */
    private $critical;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $volume;

    /**
     * Constructor.
     *
     * @param string    $name
     * @param float|int $volume
     * @param bool      $critical
     */
    public function __construct(string $name, float $volume = 1.0, bool $critical = false)
    {
        if ($volume < 0 || $volume > 1) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid volume %.2f. Must be between 0.0 and 1.0',
                $volume
            ));
        }

        $this->name = $name;
        $this->volume = $volume;
        $this->critical = $critical;
    }

    /**
     * Is critical?
     *
     * @return bool
     */
    public function isCritical(): bool
    {
        return $this->critical;
    }

    /**
     * Get the name of volume
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the volume
     *
     * @return float
     */
    public function getVolume(): float
    {
        return $this->volume;
    }
}
