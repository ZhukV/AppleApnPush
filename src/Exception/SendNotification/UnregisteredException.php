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

namespace Apple\ApnPush\Exception\SendNotification;

/**
 * The device token is inactive for the specified topic.
 */
class UnregisteredException extends SendNotificationException
{
    private \DateTimeInterface $lastConfirmed;

    public function __construct(\DateTimeInterface $lastConfirmed, string $message = 'Unregistered.')
    {
        $this->lastConfirmed = $lastConfirmed;

        parent::__construct($message);
    }

    public function getLastConfirmed(): \DateTimeInterface
    {
        return $this->lastConfirmed;
    }
}
