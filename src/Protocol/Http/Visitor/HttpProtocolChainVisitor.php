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

namespace Apple\ApnPush\Protocol\Http\Visitor;

use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Protocol\Http\Request;

class HttpProtocolChainVisitor implements HttpProtocolVisitorInterface
{
    /**
     * @var \SplPriorityQueue<int, HttpProtocolVisitorInterface>
     */
    private \SplPriorityQueue $visitors;

    public function __construct()
    {
        $this->visitors = new \SplPriorityQueue();
    }

    public function add(HttpProtocolVisitorInterface $visitor, int $priority = 0): void
    {
        $this->visitors->insert($visitor, $priority);
    }

    public function visit(Notification $notification, Request $request): Request
    {
        // Clone all visitors because \SplPriorityQueue remove object after iteration
        $visitors = clone $this->visitors;

        foreach ($visitors as $visitor) {
            $request = $visitor->visit($notification, $request);
        }

        return $request;
    }
}
