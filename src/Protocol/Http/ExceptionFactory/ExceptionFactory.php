<?php

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
    public function create(Response $response) : SendNotificationException
    {
        $content = $response->getContent();

        if (!$content) {
            return new MissingContentInResponseException();
        }

        $json = json_decode($content, true);

        if (null === $json) {
            return new InvalidResponseException(sprintf(
                'Can not parse JSON in response. Error: %d - %s',
                json_last_error(),
                json_last_error_msg()
            ));
        }

        if (!array_key_exists('reason', $json)) {
            return new MissingErrorReasonInResponseException();
        }

        $reason = $json['reason'];

        return $this->createByReason($reason, $json);
    }

    /**
     * Create exception by reason
     *
     * @param string $reason
     * @param array  $json
     *
     * @return SendNotificationException
     */
    private function createByReason(string $reason, array $json)
    {
        $reason = strtolower($reason);

        switch ($reason) {
            case 'badcollapseid':
                return new BadCollapseIdException();

            case 'baddevicetoken':
                return new BadDeviceTokenException();

            case 'badexpirationdate':
                return new BadExpirationDateException();

            case 'badmessageid':
                return new BadMessageIdException();

            case 'badpriority':
                return new BadPriorityException();

            case 'badtopic':
                return new BadTopicException();

            case 'devicetokennotfortopic':
                return new DeviceTokenNotForTopicException();

            case 'duplicateheaders':
                return new DuplicateHeadersException();

            case 'idletimeout':
                return new IdleTimeoutException();

            case 'missingdevicetoken':
                return new MissingDeviceTokenException();

            case 'missingtopic':
                return new MissingTopicException();

            case 'payloadempty':
                return new PayloadEmptyException();

            case 'topicdisallowed':
                return new TopicDisallowedException();

            case 'badcertificate':
                return new BadCertificateException();

            case 'badcertificateenvironment':
                return new BadCertificateEnvironmentException();

            case 'expiredprovidertoken':
                return new ExpiredProviderTokenException();

            case 'forbidden':
                return new ForbiddenException();

            case 'invalidprovidertoken':
                return new InvalidProviderTokenException();

            case 'missingprovidertoken':
                return new MissingProviderTokenException();

            case 'badpath':
                return new BadPathException();

            case 'methodnotallowed':
                return new MethodNotAllowedException();

            case 'unregistered':
                $timestamp = array_key_exists('timestamp', $json) ? $json['timestamp'] : 0;
                $lastConfirmed = new \DateTime('now', new \DateTimeZone('UTC'));
                $lastConfirmed->setTimestamp($timestamp);

                return new UnregisteredException($lastConfirmed);

            case 'payloadtoolarge':
                return new PayloadTooLargeException();

            case 'toomanyprovidertokenupdates':
                return new TooManyProviderTokenUpdatesException();

            case 'toomanyrequests':
                return new TooManyRequestsException();

            case 'internalservererror':
                return new InternalServerErrorException();

            case 'serviceunavailable':
                return new ServiceUnavailableException();

            case 'shutdown':
                return new ShutdownException();

            default:
                return new UndefinedErrorException();
        }
    }
}
