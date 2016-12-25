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
use Apple\ApnPush\Exception\SendMessage\InvalidResponseException;
use Apple\ApnPush\Exception\SendMessage\MethodNotAllowedException;
use Apple\ApnPush\Exception\SendMessage\MissingContentInResponseException;
use Apple\ApnPush\Exception\SendMessage\MissingDeviceTokenException;
use Apple\ApnPush\Exception\SendMessage\MissingErrorReasonInResponseException;
use Apple\ApnPush\Exception\SendMessage\MissingProviderTokenException;
use Apple\ApnPush\Exception\SendMessage\MissingTopicException;
use Apple\ApnPush\Exception\SendMessage\PayloadEmptyException;
use Apple\ApnPush\Exception\SendMessage\PayloadTooLargeException;
use Apple\ApnPush\Exception\SendMessage\SendMessageException;
use Apple\ApnPush\Exception\SendMessage\ServiceUnavailableException;
use Apple\ApnPush\Exception\SendMessage\ShutdownException;
use Apple\ApnPush\Exception\SendMessage\TooManyProviderTokenUpdatesException;
use Apple\ApnPush\Exception\SendMessage\TooManyRequestsException;
use Apple\ApnPush\Exception\SendMessage\TopicDisallowedException;
use Apple\ApnPush\Exception\SendMessage\UndefinedErrorException;
use Apple\ApnPush\Exception\SendMessage\UnregisteredException;
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
    public function create(Response $response) : SendMessageException
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
     * @return SendMessageException
     */
    private function createByReason(string $reason, array $json)
    {
        $reason = strtolower($reason);

        switch ($reason) {
            // Bad request (400)
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

            // Access denied (403)
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

            // Not found (404)
            case 'badpath':
                return new BadPathException();

            // Method not allowed (405)
            case 'methodnotallowed':
                return new MethodNotAllowedException();

            // Gone (410)
            case 'unregistered':
                $timestamp = array_key_exists('timestamp', $json) ? $json['timestamp'] : 0;
                $lastConfirmed = new \DateTime('now', new \DateTimeZone('UTC'));
                $lastConfirmed->setTimestamp($timestamp);

                return new UnregisteredException($lastConfirmed);

            // Request entity too large (413)
            case 'payloadtoolarge':
                return new PayloadTooLargeException();

            // Too many requests (429)
            case 'toomanyprovidertokenupdates':
                return new TooManyProviderTokenUpdatesException();

            case 'toomanyrequests':
                return new TooManyRequestsException();

            // Internal server error (500)
            case 'internalservererror':
                return new InternalServerErrorException();

            // Service unavailable (503)
            case 'serviceunavailable':
                return new ServiceUnavailableException();

            case 'shutdown':
                return new ShutdownException();

            default:
                return new UndefinedErrorException();
        }
    }
}
