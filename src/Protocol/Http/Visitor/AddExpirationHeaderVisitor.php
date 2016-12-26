<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Protocol\Http\Visitor;

use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Protocol\Http\Request;

/**
 * Visitor for add expiration header to request
 */
class AddExpirationHeaderVisitor implements HttpProtocolVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(Notification $notification, Request $request) : Request
    {
        $expiration = $notification->getExpiration();

        if (!$expiration->isNull()) {
            $request = $request->withHeader('apns-expiration', $expiration->getValue());
        }

        return $request;
    }
}
