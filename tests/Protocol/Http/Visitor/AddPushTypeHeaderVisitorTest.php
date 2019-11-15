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
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Model\PushType;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Visitor\AddPushTypeHeaderVisitor;
use PHPUnit\Framework\TestCase;

class AddPushTypeHeaderVisitorTest extends TestCase
{
    /**
     * @var AddPushTypeHeaderVisitor
     */
    private $visitor;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->visitor = new AddPushTypeHeaderVisitor();
    }

    /**
     * @test
     */
    public function shouldAddHeaderForPushType()
    {
        $payload = new Payload(new Aps(new Alert()));
        $notification = new Notification($payload, null, null, null, null, PushType::alert());
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($notification, $request);

        $headers = $visitedRequest->getHeaders();

        self::assertEquals([
            'apns-push-type' => PushType::TYPE_ALERT,
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
