<?php

namespace Tests\Apple\ApnPush\Model;

use Apple\ApnPush\Model\PushType;
use PHPUnit\Framework\TestCase;

class PushTypeTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccessCreate()
    {
        self::assertEquals(PushType::TYPE_ALERT, (string)PushType::alert());
        self::assertEquals(PushType::TYPE_BACKGROUND, (string)PushType::background());
    }
}
