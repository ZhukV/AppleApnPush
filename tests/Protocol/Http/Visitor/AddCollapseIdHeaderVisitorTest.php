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
use Apple\ApnPush\Model\CollapseId;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Visitor\AddCollapseIdHeaderVisitor;
use PHPUnit\Framework\TestCase;

class AddCollapseIdHeaderVisitorTest extends TestCase
{
    /**
     * @var AddCollapseIdHeaderVisitor
     */
    private $visitor;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->visitor = new AddCollapseIdHeaderVisitor();
    }

    /**
     * @test
     */
    public function shouldAddHeaderForCollapseId(): void
    {
        $payload = new Payload(new Aps(new Alert()));
        $notification = new Notification($payload, null, null, null, new CollapseId('some'));
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($notification, $request);

        $headers = $visitedRequest->getHeaders();

        self::assertEquals([
            'apns-collapse-id' => 'some',
        ], $headers);
    }

    /**
     * @test
     */
    public function shouldNotAddHeaderForCollapseId(): void
    {
        $payload = new Payload(new Aps(new Alert()));
        $notification = new Notification($payload);
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($notification, $request);

        $headers = $visitedRequest->getHeaders();
        self::assertEquals([], $headers);
    }
}
