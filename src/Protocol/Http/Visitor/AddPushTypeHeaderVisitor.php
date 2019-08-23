<?php

declare(strict_types = 1);

namespace Apple\ApnPush\Protocol\Http\Visitor;

use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Protocol\Http\Request;

/**
 * Visitor for add apns-push-type header to request
 */
class AddPushTypeHeaderVisitor implements HttpProtocolVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(Notification $notification, Request $request): Request
    {
        $pushType = $notification->getPushType();

        if ($pushType) {
            $request = $request->withHeader('apns-push-type', (string)$pushType);
        }

        return $request;
    }
}
