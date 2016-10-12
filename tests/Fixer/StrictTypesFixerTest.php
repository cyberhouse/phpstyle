<?php
namespace Cyberhouse\Phpstyle\Tests\Fixer;

/*
 * (c) 2016 by Cyberhouse GmbH
 *
 * This is free software; you can redistribute it and/or
 * modify it under the terms of the MIT License (MIT)
 *
 * For the full copyright and license information see
 * <https://opensource.org/licenses/MIT>
 */

use Cyberhouse\Phpstyle\Fixer\SingleEmptyLineFixer;
use Cyberhouse\Phpstyle\Fixer\StrictTypesFixer;

/**
 * Test the strict types fixer
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class StrictTypesFixerTest extends \PHPUnit_Framework_TestCase
{
    public function dataProvider()
    {
        $result = [];
        $dir    = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                  'Fixtures' . DIRECTORY_SEPARATOR .
                  'StrictTypes' . DIRECTORY_SEPARATOR;

        foreach (['LateType', 'NoType'] as $name) {
            $result[] = [
                $name,
                file_get_contents($dir . $name . 'Src.php'),
                file_get_contents($dir . $name . 'Expected.php'),
            ];
        }

        return $result;
    }

    /**
     * @dataProvider dataProvider
     * @param string $set
     * @param string $src
     * @param string $expected
     */
    public function testStrictTypesFixer($set, $src, $expected)
    {
        $file = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        foreach (get_class_methods(\SplFileInfo::class) as $method) {
            $file->expects($this->never())->method($method);
        }

        $fixer  = new StrictTypesFixer();
        $actual = $fixer->fix($file, $src);

        $this->assertSame($expected, $actual, 'Invalid output for data set ' . $set);
    }
}
