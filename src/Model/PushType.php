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
 * @see https://developer.apple.com/documentation/usernotifications/setting_up_a_remote_notification_server/sending_notification_requests_to_apns
 */
final class PushType
{
    const TYPE_ALERT      = 'alert';
    const TYPE_BACKGROUND = 'background';
    const TYPE_LIVEACTIVITY = 'liveactivity';

    private string $value;

    public static function alert(): self
    {
        return new self(self::TYPE_ALERT);
    }

    public static function background(): self
    {
        return new self(self::TYPE_BACKGROUND);
    }

    public static function liveactivity(): self
    {
        return new self(self::TYPE_LIVEACTIVITY);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function __construct(string $type)
    {
        if (!\in_array($type, [self::TYPE_ALERT, self::TYPE_BACKGROUND, self::TYPE_LIVEACTIVITY], true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid priority "%d". Can be "%s", "%s", or "%s".',
                $type,
                self::TYPE_BACKGROUND,
                self::TYPE_ALERT,
                self::TYPE_LIVEACTIVITY
            ));
        }

        $this->value = $type;
    }
}
