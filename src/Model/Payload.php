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

class Payload
{
    private Aps $aps;
    private array $customData;

    public function __construct(Aps $apsData, array $customData = [])
    {
        $this->aps = $apsData;
        $this->customData = $customData;
    }

    public static function createWithBody(string $body): self
    {
        return new self(new Aps(new Alert($body)));
    }

    public function withAps(Aps $aps): Payload
    {
        $cloned = clone $this;

        $cloned->aps = $aps;

        return $cloned;
    }

    public function getAps(): Aps
    {
        return $this->aps;
    }

    /**
     * Add or replace custom data by name
     *
     * @param string $name
     * @param mixed       $value
     *
     * @return self
     */
    public function withCustomData(string $name, $value): self
    {
        if ($value && !\is_array($value) && !\is_scalar($value) && !$value instanceof \JsonSerializable) {
            throw new \InvalidArgumentException(sprintf(
                'The custom data value should be a scalar or \JsonSerializable instance, but "%s" given.',
                \is_object($value) ? \get_class($value) : \gettype($value)
            ));
        }

        $cloned = clone $this;

        $cloned->customData[$name] = $value;

        return $cloned;
    }

    public function getCustomData(): array
    {
        return $this->customData;
    }
}
