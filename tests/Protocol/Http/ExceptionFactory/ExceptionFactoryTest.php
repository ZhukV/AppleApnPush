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
        throw $this->exceptionFactory->create($response);
    }

    /**
     * @test
     *
     * @expectedException \Apple\ApnPush\Exception\SendNotification\InvalidResponseException
     */
    public function shouldFailIfInvalidJson()
    {
        $response = new Response(400, '{"some}');
        throw $this->exceptionFactory->create($response);
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
        throw $this->exceptionFactory->create($response);
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
        $json = array_merge([
            'reason' => $reason,
        ], $extra);

        $response = new Response(200, json_encode($json));
        $exception = $this->exceptionFactory->create($response);

        self::assertEquals($expectedException, $exception);
    }

    /**
     * Provide reasons
     *
     * @return array
     */
    public function provideReasons()
    {
        $lastUse = \DateTime::createFromFormat('!Y/m/d', '2017/01/01');

        return [
            ['BadCollapseId', new BadCollapseIdException()],
            ['BadDeviceToken', new BadDeviceTokenException()],
            ['BadExpirationDate', new BadExpirationDateException()],
            ['BadMessageId', new BadMessageIdException()],
            ['BadPriority', new BadPriorityException()],
            ['BadTopic', new BadTopicException()],
            ['DeviceTokenNotForTopic', new DeviceTokenNotForTopicException()],
            ['DuplicateHeaders', new DuplicateHeadersException()],
            ['IdleTimeout', new IdleTimeoutException()],
            ['MissingDeviceToken', new MissingDeviceTokenException()],
            ['MissingTopic', new MissingTopicException()],
            ['PayloadEmpty', new PayloadEmptyException()],
            ['TopicDisallowed', new TopicDisallowedException()],
            ['BadCertificate', new BadCertificateException()],
            ['BadCertificateEnvironment', new BadCertificateEnvironmentException()],
            ['ExpiredProviderToken', new ExpiredProviderTokenException()],
            ['Forbidden', new ForbiddenException()],
            ['InvalidProviderToken', new InvalidProviderTokenException()],
            ['MissingProviderToken', new MissingProviderTokenException()],
            ['BadPath', new BadPathException()],
            ['MethodNotAllowed', new MethodNotAllowedException()],
            ['Unregistered', new UnregisteredException($lastUse), ['timestamp' => (int) $lastUse->format('U')]],
            ['PayloadTooLarge', new PayloadTooLargeException()],
            ['TooManyProviderTokenUpdates', new TooManyProviderTokenUpdatesException()],
            ['TooManyRequests', new TooManyRequestsException()],
            ['InternalServerError', new InternalServerErrorException()],
            ['ServiceUnavailable', new ServiceUnavailableException()],
            ['Shutdown', new ShutdownException()],
            ['some', new UndefinedErrorException()],
        ];
    }
}
