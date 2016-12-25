<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Model;

/**
 * UUID identifier for APN
 */
class ApnId
{
    /**
     * @var string
     */
    private $value;

    /**
     * Create ApnID from null
     *
     * @return ApnId
     */
    public static function fromNull() : ApnId
    {
        return new self('');
    }

    /**
     * Create from id
     *
     * @param string $id
     *
     * @return ApnId
     *
     * @throws \InvalidArgumentException
     */
    public static function fromId(string $id) : ApnId
    {
        self::validateId($id);

        return new self($id);
    }

    /**
     * Is null Apn ID?
     *
     * @return bool
     */
    public function isNull()
    {
        return $this->value === '';
    }

    /**
     * Get value
     *
     * @return string
     *
     * @throws \LogicException
     */
    public function getValue() : string
    {
        if ($this->isNull()) {
            throw new \LogicException('Can not get value from null Apn ID object.');
        }

        return (string) $this->value;
    }

    /**
     * Constructor.
     *
     * @param string $id
     */
    private function __construct($id)
    {
        $this->value = $id;
    }

    /**
     * Validate ID
     *
     * @param string $id
     *
     * @throws \InvalidArgumentException
     */
    private static function validateId(string $id)
    {
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $id)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid UUID identifier "%s".',
                $id
            ));
        }
    }
}
