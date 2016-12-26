<?php

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
 * Payload model
 */
class Payload
{
    /**
     * @var Aps
     */
    private $aps;

    /**
     * @var array
     */
    private $customData;

    /**
     * Constructor.
     *
     * @param Aps   $apsData
     * @param array $customData
     */
    public function __construct(Aps $apsData, array $customData = [])
    {
        $this->aps = $apsData;
        $this->customData = $customData;
    }

    /**
     * Set aps
     *
     * @param Aps $aps
     *
     * @return Payload
     */
    public function withAps(Aps $aps) : Payload
    {
        $cloned = clone $this;

        $cloned->aps = $aps;

        return $cloned;
    }

    /**
     * Get APS data
     *
     * @return Aps
     */
    public function getAps() : Aps
    {
        return $this->aps;
    }

    /**
     * Add or replace custom data
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return Payload
     *
     * @throws \InvalidArgumentException
     */
    public function withCustomData(string $name, $value)
    {
        if ($value && !is_array($value) && !is_scalar($value) && !$value instanceof \JsonSerializable) {
            throw new \InvalidArgumentException(sprintf(
                'The custom data value should be a scalar or \JsonSerializable instance, but "%s" given.',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        $cloned = clone $this;

        $cloned->customData[$name] = $value;

        return $cloned;
    }

    /**
     * Get custom data
     *
     * @return array
     */
    public function getCustomData()
    {
        return $this->customData;
    }
}
