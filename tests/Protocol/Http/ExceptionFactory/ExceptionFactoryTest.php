<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Protocol\Http\ExceptionFactory;

use Apple\ApnPush\Exception\SendNotification\BadCertificateEnvironmentException;
use Apple\ApnPush\Exception\SendNotification\BadCertificateException;
use Apple\ApnPush\Exception\SendNotification\BadCollapseIdException;
use Apple\ApnPush\Exception\SendNotification\BadDeviceTokenException;
use Apple\ApnPush\Exception\SendNotification\BadExpirationDateException;
use Apple\ApnPush\Exception\SendNotification\BadMessageIdException;
use Apple\ApnPush\Exception\SendNotification\BadPathException;
use Apple\ApnPush\Exception\SendNotification\BadPriorityException;
use Apple\ApnPush\Exception\SendNotification\BadTopicException;
use Apple\ApnPush\Exception\SendNotification\DeviceTokenNotForTopicException;
use Apple\ApnPush\Exception\SendNotification\DuplicateHeadersException;
use Apple\ApnPush\Exception\SendNotification\ExpiredProviderTokenException;
use Apple\ApnPush\Exception\SendNotification\ForbiddenException;
use Apple\ApnPush\Exception\SendNotification\IdleTimeoutException;
use Apple\ApnPush\Exception\SendNotification\InternalServerErrorException;
use Apple\ApnPush\Exception\SendNotification\InvalidProviderTokenException;
use Apple\ApnPush\Exception\SendNotification\MethodNotAllowedException;
use Apple\ApnPush\Exception\SendNotification\MissingDeviceTokenException;
use Apple\ApnPush\Exception\SendNotification\MissingProviderTokenException;
use Apple\ApnPush\Exception\SendNotification\MissingTopicException;
use Apple\ApnPush\Exception\SendNotification\PayloadEmptyException;
use Apple\ApnPush\Exception\SendNotification\PayloadTooLargeException;
use Apple\ApnPush\Exception\SendNotification\ServiceUnavailableException;
use Apple\ApnPush\Exception\SendNotification\ShutdownException;
use Apple\ApnPush\Exception\SendNotification\TooManyProviderTokenUpdatesException;
use Apple\ApnPush\Exception\SendNotification\TooManyRequestsException;
use Apple\ApnPush\Exception\SendNotification\TopicDisallowedException;
use Apple\ApnPush\Exception\SendNotification\UndefinedErrorException;
use Apple\ApnPush\Exception\SendNotification\UnregisteredException;
use Apple\ApnPush\Protocol\Http\ExceptionFactory\ExceptionFactory;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Response;
use PHPUnit\Framework\TestCase;

class ExceptionFactoryTest extends TestCase
{
    /**
     * @var ExceptionFactory
     */
    private $exceptionFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->exceptionFactory = new ExceptionFactory();
    }

    /**
     * @test
     *
     * @expectedException \Apple\ApnPush\Exception\SendNotification\MissingContentInResponseException
     * @expectedExceptionMessage Missing content in response.
     */
    public function shouldFailIfContentNotFound()
    {
        $response = new Response(400, '');
        $request = $this->createMock(Request::class);
        throw $this->exceptionFactory->create($response, $request);
    }

    /**
     * @test
     *
     * @expectedException \Apple\ApnPush\Exception\SendNotification\InvalidResponseException
     */
    public function shouldFailIfInvalidJson()
    {
        $response = new Response(400, '{"some}');
        $request = $this->createMock(Request::class);
        throw $this->exceptionFactory->create($response, $request);
    }

    /**
     * @test
     *
     * @expectedException \Apple\ApnPush\Exception\SendNotification\MissingErrorReasonInResponseException
     * @expectedExceptionMessage Missing error reason in response.
     */
    public function shouldFailIfMissingReason()
    {
        $response = new Response(400, '{"key":"value"}');
        $request = $this->createMock(Request::class);
        throw $this->exceptionFactory->create($response, $request);
    }

    /**
     * @test
     *
     * @param string     $reason
     * @param \Exception $expectedException
     * @param array      $extra
     *
     * @dataProvider provideReasons
     */
    public function shouldSuccessCreate($reason, \Exception $expectedException, array $extra = [])
    {
        $request = array_pop($extra);

        $json = array_merge([
            'reason' => $reason,
        ], $extra);

        $response = new Response(200, json_encode($json));
        $exception = $this->exceptionFactory->create($response, $request);

        self::assertEquals(get_class($expectedException), get_class($exception));
        self::assertInstanceOf(Request::class, $exception->getRequest());
    }

    /**
     * Provide reasons
     *
     * @return array
     */
    public function provideReasons()
    {
        $lastUse = \DateTime::createFromFormat('!Y/m/d', '2017/01/01');
        $request = $this->createMock(Request::class);

        return [
            ['BadCollapseId', new BadCollapseIdException(), [$request]],
            ['BadDeviceToken', new BadDeviceTokenException(), [$request]],
            ['BadExpirationDate', new BadExpirationDateException(), [$request]],
            ['BadMessageId', new BadMessageIdException(), [$request]],
            ['BadPriority', new BadPriorityException(), [$request]],
            ['BadTopic', new BadTopicException(), [$request]],
            ['DeviceTokenNotForTopic', new DeviceTokenNotForTopicException(), [$request]],
            ['DuplicateHeaders', new DuplicateHeadersException(), [$request]],
            ['IdleTimeout', new IdleTimeoutException(), [$request]],
            ['MissingDeviceToken', new MissingDeviceTokenException(), [$request]],
            ['MissingTopic', new MissingTopicException(), [$request]],
            ['PayloadEmpty', new PayloadEmptyException(), [$request]],
            ['TopicDisallowed', new TopicDisallowedException(), [$request]],
            ['BadCertificate', new BadCertificateException(), [$request]],
            ['BadCertificateEnvironment', new BadCertificateEnvironmentException(), [$request]],
            ['ExpiredProviderToken', new ExpiredProviderTokenException(), [$request]],
            ['Forbidden', new ForbiddenException(), [$request]],
            ['InvalidProviderToken', new InvalidProviderTokenException(), [$request]],
            ['MissingProviderToken', new MissingProviderTokenException(), [$request]],
            ['BadPath', new BadPathException(), [$request]],
            ['MethodNotAllowed', new MethodNotAllowedException(), [$request]],
            ['Unregistered', new UnregisteredException($lastUse), ['timestamp' => (int) $lastUse->format('U'), $request]],
            ['PayloadTooLarge', new PayloadTooLargeException(), [$request]],
            ['TooManyProviderTokenUpdates', new TooManyProviderTokenUpdatesException(), [$request]],
            ['TooManyRequests', new TooManyRequestsException(), [$request]],
            ['InternalServerError', new InternalServerErrorException(), [$request]],
            ['ServiceUnavailable', new ServiceUnavailableException(), [$request]],
            ['Shutdown', new ShutdownException(), [$request]],
            ['some', new UndefinedErrorException(), [$request]],
        ];
    }
}
