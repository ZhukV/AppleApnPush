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

use Apple\ApnPush\Model\ApnId;
use Apple\ApnPush\Model\ApsData;
use Apple\ApnPush\Model\Message;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Visitor\AddApnIdHeaderVisitor;
use PHPUnit\Framework\TestCase;

class AddApnIdHeaderVisitorTest extends TestCase
{
    /**
     * @var AddApnIdHeaderVisitor
     */
    private $visitor;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->visitor = new AddApnIdHeaderVisitor();
    }

    /**
     * @test
     */
    public function shouldAddHeaderForApnId()
    {
        $message = new Message(new ApsData(), ApnId::fromId('550e8400-e29b-41d4-a716-446655440000'));
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($message, $request);

        $headers = $visitedRequest->getHeaders();

        self::assertEquals([
            'apns-id' => '550e8400-e29b-41d4-a716-446655440000'
        ], $headers);
    }

    /**
     * @test
     */
    public function shouldNotAddHeaderForApnId()
    {
        $message = new Message(new ApsData());
        $request = new Request('https://domain.com', '{}');

        $visitedRequest = $this->visitor->visit($message, $request);

        $headers = $visitedRequest->getHeaders();
        self::assertEquals([], $headers);
    }
}
