<?php

/**
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush;

/**
 * Default test case
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Set value to protected
     *
     * @param object $object
     * @param string $property
     * @param mixed $value
     */
    protected function setValueToProtected($object, $property, $value)
    {
        $ref = new \ReflectionProperty($object, $property);
        if (!$ref->isPublic()) {
            $ref->setAccessible(true);
        }
        $ref->setValue($object, $value);
    }
}