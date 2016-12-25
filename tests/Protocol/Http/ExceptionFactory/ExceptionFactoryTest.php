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

use Apple\ApnPush\Exception\SendMessage\BadCertificateEnvironmentException;
use Apple\ApnPush\Exception\SendMessage\BadCertificateException;
use Apple\ApnPush\Exception\SendMessage\BadCollapseIdException;
use Apple\ApnPush\Exception\SendMessage\BadDeviceTokenException;
use Apple\ApnPush\Exception\SendMessage\BadExpirationDateException;
use Apple\ApnPush\Exception\SendMessage\BadMessageIdException;
use Apple\ApnPush\Exception\SendMessage\BadPathException;
use Apple\ApnPush\Exception\SendMessage\BadPriorityException;
use Apple\ApnPush\Exception\SendMessage\BadTopicException;
use Apple\ApnPush\Exception\SendMessage\DeviceTokenNotForTopicException;
use Apple\ApnPush\Exception\SendMessage\DuplicateHeadersException;
use Apple\ApnPush\Exception\SendMessage\ExpiredProviderTokenException;
use Apple\ApnPush\Exception\SendMessage\ForbiddenException;
use Apple\ApnPush\Exception\SendMessage\IdleTimeoutException;
use Apple\ApnPush\Exception\SendMessage\InternalServerErrorException;
use Apple\ApnPush\Exception\SendMessage\InvalidProviderTokenException;
use Apple\ApnPush\Exception\SendMessage\MethodNotAllowedException;
use Apple\ApnPush\Exception\SendMessage\MissingDeviceTokenException;
use Apple\ApnPush\Exception\SendMessage\MissingProviderTokenException;
use Apple\ApnPush\Exception\SendMessage\MissingTopicException;
use Apple\ApnPush\Exception\SendMessage\PayloadEmptyException;
use Apple\ApnPush\Exception\SendMessage\PayloadTooLargeException;
use Apple\ApnPush\Exception\SendMessage\ServiceUnavailableException;
use Apple\ApnPush\Exception\SendMessage\ShutdownException;
use Apple\ApnPush\Exception\SendMessage\TooManyProviderTokenUpdatesException;
use Apple\ApnPush\Exception\SendMessage\TooManyRequestsException;
use Apple\ApnPush\Exception\SendMessage\TopicDisallowedException;
use Apple\ApnPush\Exception\SendMessage\UndefinedErrorException;
use Apple\ApnPush\Exception\SendMessage\UnregisteredException;
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
     * @expectedException \Apple\ApnPush\Exception\SendMessage\MissingContentInResponseException
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
     * @expectedException \Apple\ApnPush\Exception\SendMessage\InvalidResponseException
     */
    public function shouldFailIfInvalidJson()
    {
        $response = new Response(400, '{"some}');
        throw $this->exceptionFactory->create($response);
    }

    /**
     * @test
     *
     * @expectedException \Apple\ApnPush\Exception\SendMessage\MissingErrorReasonInResponseException
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
            ['Unregistered', new UnregisteredException(new \DateTime()), ['timestamp' => (new \DateTime())->format('U')]],
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
