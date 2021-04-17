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

use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Visitor\HttpProtocolChainVisitor;
use Apple\ApnPush\Protocol\Http\Visitor\HttpProtocolVisitorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HttpProtocolChainVisitorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCallsWithPriority(): void
    {
        $notification = $this->createMock(Notification::class);
        $request = $this->createMock(Request::class);

        $visitor1 = $this->createVisitor();
        $visitor2 = $this->createVisitor();
        $visitor3 = $this->createVisitor();

        $calls = [];

        $visitor3->expects(self::exactly(2))
            ->method('visit')
            ->with($notification, $request)
            ->willReturnCallback(function () use (&$calls, $request) {
                $calls[] = 3;

                return $request;
            });

        $visitor1->expects(self::exactly(2))
            ->method('visit')
            ->with($notification, $request)
            ->willReturnCallback(function () use (&$calls, $request) {
                $calls[] = 1;

                return $request;
            });

        $visitor2->expects(self::exactly(2))
            ->method('visit')
            ->with($notification, $request)
            ->willReturnCallback(function () use (&$calls, $request) {
                $calls[] = 2;

                return $request;
            });

        $chain = new HttpProtocolChainVisitor();
        $chain->add($visitor3, -1);
        $chain->add($visitor1, 0);
        $chain->add($visitor2, 1);

        $chain->visit($notification, $request);
        // Call to second iteration
        $chain->visit($notification, $request);

        self::assertEquals([
            2, 1, 3,
            2, 1, 3,
        ], $calls);
    }

    /**
     * @test
     */
    public function shouldNotCallNextCheckedIfPreviouslyCheckWithError(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $notification = $this->createMock(Notification::class);
        $request = $this->createMock(Request::class);

        $visitor1 = $this->createVisitor();
        $visitor2 = $this->createVisitor();
        $visitor3 = $this->createVisitor();

        $visitor1->expects(self::once())
            ->method('visit')
            ->with($notification, $request)
            ->willThrowException(new \InvalidArgumentException());

        $visitor2->expects(self::never())
            ->method('visit');

        $visitor3->expects(self::never())
            ->method('visit');

        $chain = new HttpProtocolChainVisitor();
        $chain->add($visitor1, 3);
        $chain->add($visitor2, 2);
        $chain->add($visitor3, 1);

        $chain->visit($notification, $request);
    }

    /**
     * Create visitor mock
     *
     * @return HttpProtocolVisitorInterface|MockObject
     */
    private function createVisitor(): HttpProtocolVisitorInterface
    {
        $className = sprintf(
            'HttpProtocolVisitorInterface_%s',
            \md5(\uniqid(\random_int(0, 9999), true))
        );

        $visitor = $this->getMockBuilder(HttpProtocolVisitorInterface::class)
            ->setMockClassName($className);

        return $visitor->getMock();
    }
}
