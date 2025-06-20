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

namespace Apple\ApnPush\Encoder;

use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Model\Sound;

class PayloadEncoder implements PayloadEncoderInterface
{
    public function encode(Payload $payload): string
    {
        $data = [
            'aps' => $this->convertApsToArray($payload->getAps()),
        ];

        $data = \array_merge($payload->getCustomData(), $data);

        return \json_encode($data, JSON_THROW_ON_ERROR);
    }

    private function convertApsToArray(Aps $aps): array
    {
        $data = [];

        if ($aps->getAlert()) {
            $data['alert'] = $this->convertAlertToArray($aps->getAlert());
        }

        if ($aps->getSound()) {
            $sound = $aps->getSound();

            if ($sound instanceof Sound) {
                $data['sound'] = [
                    'critical' => $sound->isCritical() ? 1 : 0,
                    'name'     => $sound->getName(),
                    'volume'   => $sound->getVolume(),
                ];
            } else {
                // Sound pass as string
                $data['sound'] = $sound;
            }
        }

        if ($aps->getBadge() !== null) {
            $data['badge'] = $aps->getBadge();
        }

        if ($aps->getCategory()) {
            $data['category'] = $aps->getCategory();
        }

        if ($aps->isContentAvailable()) {
            $data['content-available'] = 1;
        }

        if ($aps->isMutableContent()) {
            $data['mutable-content'] = 1;
        }

        if ($aps->getThreadId()) {
            $data['thread-id'] = $aps->getThreadId();
        }

        if (null !== $aps->getUrlArgs()) {
            $data['url-args'] = $aps->getUrlArgs();
        }

        return \array_merge($aps->getCustomData(), $data);
    }

    private function convertAlertToArray(Alert $alert): array
    {
        $data = [];

        if ($alert->getBodyLocalized()->getKey()) {
            $data['loc-key'] = $alert->getBodyLocalized()->getKey();
            $data['loc-args'] = $alert->getBodyLocalized()->getArgs();
        }

        if ($alert->getBody() || !$alert->getBodyLocalized()->getKey()) {
            $data['body'] = $alert->getBody();
        }

        if ($alert->getTitleLocalized()->getKey()) {
            $data['title-loc-key'] = $alert->getTitleLocalized()->getKey();
            $data['title-loc-args'] = $alert->getTitleLocalized()->getArgs();
        }

        if ($alert->getTitle()) {
            $data['title'] = $alert->getTitle();
        }

        if ($alert->getSubtitleLocalized()->getKey()) {
            $data['subtitle-loc-key'] = $alert->getSubtitleLocalized()->getKey();
            $data['subtitle-loc-args'] = $alert->getSubtitleLocalized()->getArgs();
        }

        if ($alert->getSubtitle()) {
            $data['subtitle'] = $alert->getSubtitle();
        }

        if ($alert->getActionLocalized()->getKey()) {
            $data['action-loc-key'] = $alert->getActionLocalized()->getKey();
        }

        if ($alert->getLaunchImage()) {
            $data['launch-image'] = $alert->getLaunchImage();
        }

        return $data;
    }
}
