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
 * Expiration of notification
 */
class Expiration implements ExpirationInterface
{
    /**
     * @var \DateTime
     */
    private $storeTo;

    /**
     * Constructor.
     *
     * @param \DateTime $availableTo
     */
    public function __construct(\DateTime $availableTo = null)
    {
        if ($availableTo) {
            $this->storeTo = clone $availableTo;
            $this->storeTo->setTimezone(new \DateTimeZone('UTC'));
        }
    }

    /**
     * Set the availableTo
     *
     * @param \DateTime $availableTo
     *
     * @return Expiration
     */
    public function withStoreTo(\DateTime $availableTo) : Expiration
    {
        $cloned = clone $this;

        $cloned->storeTo = clone $availableTo;

        $cloned->storeTo->setTimezone(new \DateTimeZone('UTC'));

        return $cloned;
    }

    /**
     * Get value
     *
     * @return int
     *
     * @throws \LogicException
     */
    public function getValue(): int
    {
        if (!$this->storeTo) {
            return 0;
        }

        return (int) $this->storeTo->format('U');
    }
}
