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
 * Push message
 */
class Message
{
    /**
     * @var ApnId
     */
    private $id;

    /**
     * @var ApsData
     */
    private $aps;

    /**
     * @var Priority
     */
    private $priority;

    /**
     * @var \DateTime
     */
    private $expiration;

    /**
     * @var array
     */
    private $customData;

    /**
     * Constructor.
     *
     * @param ApsData         $apsData
     * @param ApnId|null      $id
     * @param Priority|null   $priority
     * @param Expiration|null $expiration
     * @param array           $customData
     */
    public function __construct(
        ApsData $apsData,
        ApnId $id = null,
        Priority $priority = null,
        Expiration $expiration = null,
        array $customData = []
    ) {
        $this->aps = $apsData;
        $this->priority = $priority ?: Priority::fromNull();
        $this->id = $id ?: ApnId::fromNull();
        $this->expiration = $expiration ?: Expiration::fromNull();
        $this->customData = $customData;
    }

    /**
     * Get identifier of message
     *
     * @return ApnId
     */
    public function getId() : ApnId
    {
        return $this->id;
    }

    /**
     * Get APS data
     *
     * @return ApsData
     */
    public function getApsData() : ApsData
    {
        return $this->aps;
    }

    /**
     * Get priority
     *
     * @return Priority
     */
    public function getPriority() : Priority
    {
        return $this->priority;
    }

    /**
     * Get expiration
     *
     * @return Expiration
     */
    public function getExpiration() : Expiration
    {
        return $this->expiration;
    }

    /**
     * Add or replace custom data
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return Message
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
