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

namespace Apple\ApnPush\Sender;

use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Receiver;
use Apple\ApnPush\Protocol\ProtocolInterface;

/**
 * Default notification sender
 */
class Sender implements SenderInterface
{
    /**
     * @var ProtocolInterface
     */
    private $protocol;

    /**
     * Constructor.
     *
     * @param ProtocolInterface $protocol
     */
    public function __construct(ProtocolInterface $protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Receiver $receiver, Notification $notification, bool $sandbox = false): void
    {
        $this->protocol->send($receiver, $notification, $sandbox);
    }
}
