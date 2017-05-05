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
use PHPUnit\Framework\TestCase;

class ApsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate()
    {
        $aps = new Aps(new Alert());

        self::assertEmpty($aps->getCategory());
        self::assertEmpty($aps->getSound());
        self::assertEmpty($aps->getBadge());
        self::assertEmpty($aps->getThreadId());
    }

    /**
     * @test
     */
    public function shouldSuccessChangeAlert()
    {
        $aps = new Aps(new Alert());
        $apsWithChangedAlert = $aps->withAlert(new Alert('some'));

        self::assertEquals(new Alert('some'), $apsWithChangedAlert->getAlert());
        self::assertNotEquals(spl_object_hash($aps), spl_object_hash($apsWithChangedAlert));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeCategory()
    {
        $aps = new Aps(new Alert());
        $apsWithChangedCategory = $aps->withCategory('some');

        self::assertEquals('some', $apsWithChangedCategory->getCategory());
        self::assertNotEquals(spl_object_hash($aps), spl_object_hash($apsWithChangedCategory));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeSound()
    {
        $aps = new Aps(new Alert());
        $apsWithChangedSound = $aps->withSound('some');

        self::assertEquals('some', $apsWithChangedSound->getSound());
        self::assertNotEquals(spl_object_hash($aps), spl_object_hash($apsWithChangedSound));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeBadge()
    {
        $aps = new Aps(new Alert());
        $apsWithChangedBadge = $aps->withBadge(123);

        self::assertEquals(123, $apsWithChangedBadge->getBadge());
        self::assertNotEquals(spl_object_hash($aps), spl_object_hash($apsWithChangedBadge));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeContentAvailable()
    {
        $aps = new Aps(new Alert());
        $apsWithChangedContentAvailable = $aps->withContentAvailable(true);

        self::assertTrue($apsWithChangedContentAvailable->isContentAvailable());
        self::assertNotEquals(spl_object_hash($aps), spl_object_hash($apsWithChangedContentAvailable));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeThread()
    {
        $aps = new Aps(new Alert());
        $apsWithChangedThread = $aps->withThreadId('some');

        self::assertEquals('some', $apsWithChangedThread->getThreadId());
        self::assertNotEquals(spl_object_hash($aps), spl_object_hash($apsWithChangedThread));
    }
}
