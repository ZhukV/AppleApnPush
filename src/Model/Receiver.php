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

class Receiver
{
    private DeviceToken $token;
    private string $topic;

    public function __construct(DeviceToken $token, string $topic)
    {
        $this->token = $token;
        $this->topic = $topic;
    }

    public function getToken(): DeviceToken
    {
        return $this->token;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }
}
