<?php

declare(strict_types=1);

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
    /**
     * @var \DateTime
     */
    private $lastConfirmed;

    /**
     * Constructor.
     *
     * @param \DateTime $lastConfirmed
     * @param string    $message
     */
    public function __construct(\DateTime $lastConfirmed, string $message = 'Unregistered.')
    {
        $this->lastConfirmed = $lastConfirmed;

        parent::__construct($message);
    }

    /**
     * Get last confirmed
     *
     * @return \DateTime
     */
    public function getLastConfirmed(): \DateTime
    {
        return $this->lastConfirmed;
    }
}
