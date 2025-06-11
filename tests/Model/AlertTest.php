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

namespace Tests\Apple\ApnPush\Model;

use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\Localized;
use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate(): void
    {
        $alert = new Alert();

        self::assertEmpty($alert->getTitle());
        self::assertEmpty($alert->getSubtitle());
        self::assertEmpty($alert->getBody());
        self::assertEquals(new Localized(''), $alert->getTitleLocalized());
        self::assertEquals(new Localized(''), $alert->getSubtitleLocalized());
        self::assertEquals(new Localized(''), $alert->getBodyLocalized());
        self::assertEquals(new Localized(''), $alert->getActionLocalized());
        self::assertEmpty($alert->getLaunchImage());
    }

    /**
     * @test
     */
    public function shouldSuccessChangeTitle(): void
    {
        $alert = new Alert();
        $alertWithChangedTitle = $alert->withTitle('some');

        self::assertEquals('some', $alertWithChangedTitle->getTitle());
        self::assertNotEquals(spl_object_hash($alert), spl_object_hash($alertWithChangedTitle));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeSubtitle(): void
    {
        $alert = new Alert();
        $alertWithChangedSubtitle = $alert->withSubtitle('some');

        self::assertEquals('some', $alertWithChangedSubtitle->getSubtitle());
        self::assertNotEquals(spl_object_hash($alert), spl_object_hash($alertWithChangedSubtitle));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeBody(): void
    {
        $alert = new Alert();
        $alertWithChangedBody = $alert->withBody('some');

        self::assertEquals('some', $alertWithChangedBody->getBody());
        self::assertNotEquals(spl_object_hash($alert), spl_object_hash($alertWithChangedBody));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeLaunchImage(): void
    {
        $alert = new Alert();
        $alertWithChangedLaunchImage = $alert->withLaunchImage('some.png');

        self::assertEquals('some.png', $alertWithChangedLaunchImage->getLaunchImage());
        self::assertNotEquals(spl_object_hash($alert), spl_object_hash($alertWithChangedLaunchImage));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeLocalizedTitle(): void
    {
        $alert = new Alert();
        $alertWithChangedLocalizedTitle = $alert->withLocalizedTitle(new Localized('some', ['key' => 'value']));

        self::assertEquals(new Localized('some', ['key' => 'value']), $alertWithChangedLocalizedTitle->getTitleLocalized());
        self::assertNotEquals(spl_object_hash($alert), spl_object_hash($alertWithChangedLocalizedTitle));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeLocalizedSubtitle(): void
    {
        $alert = new Alert();
        $alertWithChangedLocalizedSubtitle = $alert->withLocalizedSubtitle(new Localized('some', ['key' => 'value']));

        self::assertEquals(new Localized('some', ['key' => 'value']), $alertWithChangedLocalizedSubtitle->getSubtitleLocalized());
        self::assertNotEquals(spl_object_hash($alert), spl_object_hash($alertWithChangedLocalizedSubtitle));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeLocalizedBody(): void
    {
        $alert = new Alert();
        $alertWithChangedLocalizedBody = $alert->withBodyLocalized(new Localized('some', ['key' => 'value']));

        self::assertEquals(new Localized('some', ['key' => 'value']), $alertWithChangedLocalizedBody->getBodyLocalized());
        self::assertNotEquals(spl_object_hash($alert), spl_object_hash($alertWithChangedLocalizedBody));
    }

    /**
     * @test
     */
    public function shouldSuccessChangeLocalizedAction(): void
    {
        $alert = new Alert();
        $alertWithChangedLocalizedAction = $alert->withActionLocalized(new Localized('some', ['key' => 'value']));

        self::assertEquals(new Localized('some', ['key' => 'value']), $alertWithChangedLocalizedAction->getActionLocalized());
        self::assertNotEquals(spl_object_hash($alert), spl_object_hash($alertWithChangedLocalizedAction));
    }
}
