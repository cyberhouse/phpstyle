<?php
namespace Cyberhouse\Phpstyle;

/*
 * This file is (c) 2017 by Cyberhouse GmbH
 *
 * It is free software; you can redistribute it and/or
 * modify it under the terms of the MIT License (MIT)
 *
 * For the full copyright and license information see
 * <https://opensource.org/licenses/MIT>
 */

/**
 * Base of a test case that provides some common helpers
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 */
abstract class AbstractTestcase extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets the given data as property value in the object
     *
     * Uses reflection to set properties of protected and
     * private properties.
     *
     * @param object $obj
     * @param string $property
     * @param mixed $value
     */
    protected function inject($obj, $property, &$value)
    {
        $reflObj = new \ReflectionObject($obj);
        $reflProp = $reflObj->getProperty($property);

        $reflProp->setAccessible(true);
        $reflProp->setValue($obj, $value);
    }

    /**
     * Generates a basic mock for the given class
     *
     * Constructor is disabled!
     *
     * @param string $cls
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function makeMock($cls)
    {
        return $this->getMockBuilder($cls)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
