<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\Apple\ApnPush\Model;

use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\ApnId;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\CollapseId;
use Apple\ApnPush\Model\Expiration;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Model\Priority;
use Apple\ApnPush\Model\PushType;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate()
    {
        $notification = new Notification($this->createPayload());

        self::assertEquals($this->createPayload(), $notification->getPayload());
        self::assertNull($notification->getApnId());
        self::assertNull($notification->getExpiration());
        self::assertNull($notification->getPriority());
    }

    /**
     * @test
     */
    public function shouldSuccessCreateWithBody()
    {
        $notification = Notification::createWithBody('some');

        self::assertEquals(new Notification(new Payload(new Aps(new Alert('some')))), $notification);
    }

    /**
     * @test
     */
    public function shouldSuccessChangePayload()
    {
        $notification = new Notification($this->createPayload());
        $notificationWithChangedPayload = $notification->withPayload(new Payload(new Aps(new Alert('some'))));

        self::assertEquals(new Payload(new Aps(new Alert('some'))), $notificationWithChangedPayload->getPayload());
        self::assertNotEquals(spl_object_hash($notification), spl_object_hash($notificationWithChangedPayload));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeApnId()
    {
        $notification = new Notification($this->createPayload());
        $notificationWithChangedApnId = $notification->withApnId(new ApnId('550e8400-e29b-41d4-a716-446655440000'));

        self::assertEquals(new ApnId('550e8400-e29b-41d4-a716-446655440000'), $notificationWithChangedApnId->getApnId());
        self::assertNotEquals(spl_object_hash($notification), spl_object_hash($notificationWithChangedApnId));
    }

    /**
     * @test
     */
    public function shouldSuccessChangePriority()
    {
        $notification = new Notification($this->createPayload());
        $notificationWithChangedPriority = $notification->withPriority(new Priority(5));

        self::assertEquals(new Priority(5), $notificationWithChangedPriority->getPriority());
        self::assertNotEquals(spl_object_hash($notification), spl_object_hash($notificationWithChangedPriority));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeExpiration()
    {
        $now = new \DateTime();

        $notification = new Notification($this->createPayload());
        $notificationWithChangedExpiration = $notification->withExpiration(new Expiration($now));

        self::assertEqualsWithDelta(new Expiration($now), $notificationWithChangedExpiration->getExpiration(), 2);
        self::assertNotEquals(spl_object_hash($notification), spl_object_hash($notificationWithChangedExpiration));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeCollapseId()
    {
        $notification = new Notification($this->createPayload());
        $notificationWithChangedCollapseId = $notification->withCollapseId(new CollapseId('some'));

        self::assertEquals(new CollapseId('some'), $notificationWithChangedCollapseId->getCollapseId());
        self::assertNotEquals(spl_object_hash($notification), spl_object_hash($notificationWithChangedCollapseId));
    }

    /**
     * @test
     */
    public function shouldSuccessChangePushType()
    {
        $notification = new Notification($this->createPayload());
        $notificationWithChangedPushType = $notification->withPushType(PushType::alert());

        self::assertEquals(PushType::alert(), $notificationWithChangedPushType->getPushType());
        self::assertNotEquals(spl_object_hash($notification), spl_object_hash($notificationWithChangedPushType));
    }

    /**
     * Create the default payload
     *
     * @return Payload
     */
    private function createPayload(): Payload
    {
        return new Payload(new Aps(new Alert()));
    }
}
