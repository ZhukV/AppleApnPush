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
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\Sound;
use PHPUnit\Framework\TestCase;

class ApsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate(): void
    {
        $aps = new Aps(new Alert());

        self::assertEmpty($aps->getCategory());
        self::assertEmpty($aps->getSound());
        self::assertEmpty($aps->getBadge());
        self::assertEmpty($aps->getThreadId());
        self::assertEmpty($aps->getUrlArgs());
    }

    /**
     * @test
     */
    public function shouldSuccessChangeAlert(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedAlert = $aps->withAlert(new Alert('some'));

        self::assertEquals(new Alert('some'), $apsWithChangedAlert->getAlert());
        self::assertNotEquals(\spl_object_hash($aps), \spl_object_hash($apsWithChangedAlert));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeCategory(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedCategory = $aps->withCategory('some');

        self::assertEquals('some', $apsWithChangedCategory->getCategory());
        self::assertNotEquals(\spl_object_hash($aps), \spl_object_hash($apsWithChangedCategory));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeSoundIfSoundString(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedSound = $aps->withSound('some');

        self::assertEquals('some', $apsWithChangedSound->getSound());
        self::assertNotEquals(\spl_object_hash($aps), \spl_object_hash($apsWithChangedSound));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeSoundIfSoundObject(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedSound = $aps->withSound(new Sound('foo', 0.5, true));

        self::assertEquals(new Sound('foo', 0.5, true), $apsWithChangedSound->getSound());
        self::assertNotEquals(\spl_object_hash($aps), \spl_object_hash($apsWithChangedSound));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfSetInvalidSound(): void
    {
        $aps = new Aps(new Alert());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Sound must be a string or Apple\ApnPush\Model\Sound object, but "stdClass" given.');

        $aps->withSound(new \stdClass());
    }

    /**
     * @test
     */
    public function shouldSuccessChangeBadge(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedBadge = $aps->withBadge(123);

        self::assertEquals(123, $apsWithChangedBadge->getBadge());
        self::assertNotEquals(\spl_object_hash($aps), \spl_object_hash($apsWithChangedBadge));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeContentAvailable(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedContentAvailable = $aps->withContentAvailable(true);

        self::assertTrue($apsWithChangedContentAvailable->isContentAvailable());
        self::assertNotEquals(spl_object_hash($aps), spl_object_hash($apsWithChangedContentAvailable));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeMutableContent(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedMutableContent = $aps->withMutableContent(true);

        self::assertTrue($apsWithChangedMutableContent->isMutableContent());
        self::assertNotEquals(spl_object_hash($aps), spl_object_hash($apsWithChangedMutableContent));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeThread(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedThread = $aps->withThreadId('some');

        self::assertEquals('some', $apsWithChangedThread->getThreadId());
        self::assertNotEquals(\spl_object_hash($aps), \spl_object_hash($apsWithChangedThread));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeUrlArgs(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedThread = $aps->withUrlArgs(['some', '123']);

        self::assertEquals(['some', '123'], $apsWithChangedThread->getUrlArgs());
        self::assertNotEquals(\spl_object_hash($aps), \spl_object_hash($apsWithChangedThread));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeUrlArgsEmpty(): void
    {
        $aps = new Aps(new Alert());
        $apsWithChangedThread = $aps->withUrlArgs([]);

        self::assertNull($aps->getUrlArgs());
        self::assertEquals([], $apsWithChangedThread->getUrlArgs());
        self::assertNotEquals(\spl_object_hash($aps), \spl_object_hash($apsWithChangedThread));
    }
}
