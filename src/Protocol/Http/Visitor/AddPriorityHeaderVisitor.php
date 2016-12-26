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
 * Visitor for add priority header to request
 */
class AddPriorityHeaderVisitor implements HttpProtocolVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(Notification $notification, Request $request) : Request
    {
        $priority = $notification->getPriority();

        if (!$priority->isNull()) {
            $request = $request->withHeader('apns-priority', $priority->getValue());
        }

        return $request;
    }
}
