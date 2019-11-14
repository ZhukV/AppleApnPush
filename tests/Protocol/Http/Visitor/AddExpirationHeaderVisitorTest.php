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
use Apple\ApnPush\Model\Expiration;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Visitor\AddExpirationHeaderVisitor;
use PHPUnit\Framework\TestCase;

class AddExpirationHeaderVisitorTest extends TestCase
{
    /**
     * @var AddExpirationHeaderVisitor
     */
    private $visitor;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->visitor = new AddExpirationHeaderVisitor();
    }

    /**
     * @test
     */
    public function shouldAddHeaderForExpiration()
    {
        $storeTo = new \DateTime();

        $payload = new Payload(new Aps(new Alert()));
        $notification = new Notification($payload, null, null, new Expiration($storeTo));
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($notification, $request);

        $headers = $visitedRequest->getHeaders();

        self::assertEquals([
            'apns-expiration' => $storeTo->format('U'),
        ], $headers);
    }

    /**
     * @test
     */
    public function shouldAddHeaderForZeroExpiration()
    {
        $payload = new Payload(new Aps(new Alert()));
        $notification = new Notification($payload, null, null, new Expiration());
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($notification, $request);

        $headers = $visitedRequest->getHeaders();

        self::assertEquals([
            'apns-expiration' => 0,
        ], $headers);
    }

    /**
     * @test
     */
    public function shouldNotAddHeaderForExpiration()
    {
        $payload = new Payload(new Aps(new Alert()));
        $notification = new Notification($payload);
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($notification, $request);

        $headers = $visitedRequest->getHeaders();
        self::assertEquals([], $headers);
    }
}
