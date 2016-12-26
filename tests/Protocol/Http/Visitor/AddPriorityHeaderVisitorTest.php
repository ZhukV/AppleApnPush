<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Protocol\Http\Visitor;

use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\ApnId;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Model\Priority;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Visitor\AddPriorityHeaderVisitor;
use PHPUnit\Framework\TestCase;

class AddPriorityHeaderVisitorTest extends TestCase
{
    /**
     * @var AddPriorityHeaderVisitor
     */
    private $visitor;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->visitor = new AddPriorityHeaderVisitor();
    }

    /**
     * @test
     */
    public function shouldAddHeaderForPriority()
    {
        $payload = new Payload(new Aps(new Alert()));
        $notification = new Notification($payload, ApnId::fromNull(), Priority::immediately());
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($notification, $request);

        $headers = $visitedRequest->getHeaders();

        self::assertEquals([
            'apns-priority' => 10
        ], $headers);
    }

    /**
     * @test
     */
    public function shouldNotAddHeaderForPriority()
    {
        $payload = new Payload(new Aps(new Alert()));
        $notification = new Notification($payload);
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($notification, $request);

        $headers = $visitedRequest->getHeaders();
        self::assertEquals([], $headers);
    }
}
