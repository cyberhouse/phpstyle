<?php
namespace Cyberhouse\Phpstyle\Tests\Fixer;

/*
 * (c) 2017 by Cyberhouse GmbH
 *
 * This is free software; you can redistribute it and/or
 * modify it under the terms of the MIT License (MIT)
 *
 * For the full copyright and license information see
 * <https://opensource.org/licenses/MIT>
 */

use Cyberhouse\Phpstyle\Fixer\NamespaceFirstFixer;
use PHPUnit\Framework\TestCase;

/**
 * Check if namespace_first fixer always puts the namespace on top
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class NamespaceFirstFixerTest extends TestCase
{
    /**
     * @return array
     */
    public function namespaceDataProvider()
    {
        $result = [];
        $sets = ['NamespaceAfterComment', 'NamespaceFirst', 'NamespaceNoClass'];
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                  'Fixtures' . DIRECTORY_SEPARATOR . 'NamespaceFixer' . DIRECTORY_SEPARATOR;

        foreach ($sets as $set) {
            $result[] = [
                $set,
                file_get_contents($dir . $set . 'Src.php'),
                file_get_contents($dir . $set . 'Expected.php'),
            ];
        }

        return $result;
    }
    /**
     * @dataProvider namespaceDataProvider
     * @param string $data
     * @param string $expected
     */
    public function testNamespaceFirstFixerPutsNamespaceAfterOpenTag($set, $data, $expected)
    {
        $file = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        foreach (get_class_methods(\SplFileInfo::class) as $method) {
            $file->expects($this->never())->method($method);
        }

        $fixer = new NamespaceFirstFixer();
        $actual = $fixer->fix($file, $data);
        $this->assertSame($expected, $actual, 'Unexpected result for data set ' . $set);

        $actual = $fixer->fix($file, $actual);
        $this->assertSame($expected, $actual, 'Corrected data was changed with set ' . $set);
    }

    public function testNamespaceCorrectDoesNotChangeCode()
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                 'Fixtures' . DIRECTORY_SEPARATOR . 'NamespaceFixer' . DIRECTORY_SEPARATOR;
        $data = file_get_contents($dir . 'NamespaceCorrect.php');

        $file = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        foreach (get_class_methods(\SplFileInfo::class) as $method) {
            $file->expects($this->never())->method($method);
        }

        $fixer = new NamespaceFirstFixer();
        $actual = $fixer->fix($file, $data);

        $this->assertSame($data, $actual);
    }
}
