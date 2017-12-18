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

namespace Apple\ApnPush\Protocol\Http\ExceptionFactory;

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
use Apple\ApnPush\Exception\SendNotification\InvalidResponseException;
use Apple\ApnPush\Exception\SendNotification\MethodNotAllowedException;
use Apple\ApnPush\Exception\SendNotification\MissingContentInResponseException;
use Apple\ApnPush\Exception\SendNotification\MissingDeviceTokenException;
use Apple\ApnPush\Exception\SendNotification\MissingErrorReasonInResponseException;
use Apple\ApnPush\Exception\SendNotification\MissingProviderTokenException;
use Apple\ApnPush\Exception\SendNotification\MissingTopicException;
use Apple\ApnPush\Exception\SendNotification\PayloadEmptyException;
use Apple\ApnPush\Exception\SendNotification\PayloadTooLargeException;
use Apple\ApnPush\Exception\SendNotification\SendNotificationException;
use Apple\ApnPush\Exception\SendNotification\ServiceUnavailableException;
use Apple\ApnPush\Exception\SendNotification\ShutdownException;
use Apple\ApnPush\Exception\SendNotification\TooManyProviderTokenUpdatesException;
use Apple\ApnPush\Exception\SendNotification\TooManyRequestsException;
use Apple\ApnPush\Exception\SendNotification\TopicDisallowedException;
use Apple\ApnPush\Exception\SendNotification\UndefinedErrorException;
use Apple\ApnPush\Exception\SendNotification\UnregisteredException;
use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Response;

/**
 * Default exception factory for create exception via response.
 * For all error codes, please see: https://developer.apple.com/library/prerelease/content/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/CommunicatingwithAPNs.html#//apple_ref/doc/uid/TP40008194-CH11-SW17
 */
class ExceptionFactory implements ExceptionFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(Response $response, Request $request): SendNotificationException
    {
        $content = $response->getContent();

        if (!$content) {
            return (new MissingContentInResponseException())->setRequest($request);
        }

        $json = json_decode($content, true);

        if (null === $json) {
            return (new InvalidResponseException(sprintf(
                'Can not parse JSON in response. Error: %d - %s',
                json_last_error(),
                json_last_error_msg()
            )))->setRequest($request);
        }

        if (!array_key_exists('reason', $json)) {
            return (new MissingErrorReasonInResponseException())->setRequest($request);
        }

        $reason = $json['reason'];

        return $this->createByReason($reason, $json, $request);
    }

    /**
     * Create exception by reason
     *
     * @param string $reason
     * @param array $json
     *
     * @param Request $request
     * @return SendNotificationException
     */
    private function createByReason(string $reason, array $json, Request $request): SendNotificationException
    {
        $reason = strtolower($reason);

        switch ($reason) {
            case 'badcollapseid':
                $exception = new BadCollapseIdException();
                break;
            case 'baddevicetoken':
                $exception = new BadDeviceTokenException();
                break;
            case 'badexpirationdate':
                $exception = (new BadExpirationDateException());
                break;
            case 'badmessageid':
                $exception = new BadMessageIdException();
                break;
            case 'badpriority':
                $exception = new BadPriorityException();
                break;
            case 'badtopic':
                $exception = new BadTopicException();
                break;
            case 'devicetokennotfortopic':
                $exception = new DeviceTokenNotForTopicException();
                break;
            case 'duplicateheaders':
                $exception = new DuplicateHeadersException();
                break;
            case 'idletimeout':
                $exception = new IdleTimeoutException();
                break;
            case 'missingdevicetoken':
                $exception = new MissingDeviceTokenException();
                break;
            case 'missingtopic':
                $exception = new MissingTopicException();
                break;
            case 'payloadempty':
                $exception = new PayloadEmptyException();
                break;
            case 'topicdisallowed':
                $exception = new TopicDisallowedException();
                break;
            case 'badcertificate':
                $exception = new BadCertificateException();
                break;
            case 'badcertificateenvironment':
                $exception = new BadCertificateEnvironmentException();
                break;
            case 'expiredprovidertoken':
                $exception = new ExpiredProviderTokenException();
                break;
            case 'forbidden':
                $exception = new ForbiddenException();
                break;
            case 'invalidprovidertoken':
                $exception = new InvalidProviderTokenException();
                break;
            case 'missingprovidertoken':
                $exception = new MissingProviderTokenException();
                break;
            case 'badpath':
                $exception = new BadPathException();
                break;
            case 'methodnotallowed':
                $exception = new MethodNotAllowedException();
                break;
            case 'unregistered':
                $timestamp = array_key_exists('timestamp', $json) ? $json['timestamp'] : 0;
                $lastConfirmed = new \DateTime('now', new \DateTimeZone('UTC'));
                $lastConfirmed->setTimestamp($timestamp);

                $exception = new UnregisteredException($lastConfirmed);
                break;
            case 'payloadtoolarge':
                $exception = new PayloadTooLargeException();
                break;
            case 'toomanyprovidertokenupdates':
                $exception = new TooManyProviderTokenUpdatesException();
                break;
            case 'toomanyrequests':
                $exception = new TooManyRequestsException();
                break;
            case 'internalservererror':
                $exception = new InternalServerErrorException();
                break;
            case 'serviceunavailable':
                $exception = new ServiceUnavailableException();
                break;
            case 'shutdown':
                $exception = new ShutdownException();
                break;
            default:
                $exception = new UndefinedErrorException();
        }

        return $exception->setRequest($request);
    }
}
