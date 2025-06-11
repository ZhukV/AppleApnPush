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

class Expiration
{
    private ?\DateTimeInterface $storeTo = null;

    public function __construct(?\DateTimeInterface $availableTo = null)
    {
        if ($availableTo) {
            $this->storeTo = new \DateTime($availableTo->format(\DateTimeInterface::ATOM));
            $this->storeTo->setTimezone(new \DateTimeZone('UTC'));
        }
    }

    public function getValue(): int
    {
        if (!$this->storeTo) {
            return 0;
        }

        return (int) $this->storeTo->format('U');
    }
}
