<?php

declare(strict_types = 1);

namespace Apple\ApnPush\Model;

/**
 * The type of the notification
 * @see https://developer.apple.com/documentation/usernotifications/setting_up_a_remote_notification_server/sending_notification_requests_to_apns
 */
final class PushType
{
    const TYPE_ALERT      = 'alert';
    const TYPE_BACKGROUND = 'background';
    
    private $value;

    public static function alert(): PushType
    {
        return new self(self::TYPE_ALERT);
    }

    public static function background(): PushType
    {
        return new self(self::TYPE_BACKGROUND);
    }
    
    public function __toString(): string
    {
        return $this->value;
    }
    
    /**
     * @param string $type
     * @throws \InvalidArgumentException
     */
    private function __construct(string $type)
    {
        if (!in_array($type, [self::TYPE_ALERT, self::TYPE_BACKGROUND], true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid priority "%d". Can be "%s" or "%s".',
                    $type,
                    self::TYPE_BACKGROUND,
                    self::TYPE_ALERT
                )
            );
        }
        
        $this->value = $type;
    }
}
