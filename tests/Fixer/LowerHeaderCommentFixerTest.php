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

use Cyberhouse\Phpstyle\Fixer\LowerHeaderCommentFixer;

/**
 * Test the LowerHeaderComment fixer
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class LowerHeaderCommentFixerTest extends \PHPUnit_Framework_TestCase
{
    public function headerCommentTestData()
    {
        $header = <<<EOF
            This file is (c) 2016 by Cyberhouse GmbH

            It is free software; you can redistribute it and/or
            modify it under the terms of the Apache License 2.0

            For the full copyright and license information see
            <http://www.apache.org/licenses/LICENSE-2.0>
EOF;
        $sets = ['Correct', 'NamespaceFirst', 'NamespaceAfter', 'NoComment'];
        $res  = [];
        $dir  = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                'Fixtures' . DIRECTORY_SEPARATOR . 'HeaderCommentFixer' . DIRECTORY_SEPARATOR;

        $exp  = file_get_contents($dir . 'Expected.php');

        foreach ($sets as $i => $set) {
            $res[] = [
                "$i: $set",
                file_get_contents($dir . $set . '.php'),
                $header,
                $exp,
            ];
        }
        return $res;
    }
    /**
     * @dataProvider headerCommentTestData
     * @param string $name
     * @param string $src
     * @param string $header
     * @param string $expected
     */
    public function testHeaderCommentInsertedCorrectly($name, $src, $header, $expected)
    {
        $fixer = new LowerHeaderCommentFixer();
        $file  = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        foreach (get_class_methods(\SplFileInfo::class) as $method) {
            $file->expects($this->never())->method($method);
        }

        LowerHeaderCommentFixer::setHeader($header);

        $actual = $fixer->fix($file, $src);

        $this->assertSame($expected, $actual, 'HeaderCommentFixer failed with data set ' . $name);

        $actual = $fixer->fix($file, $actual);

        $this->assertSame(
            $expected,
            $actual,
            'HeaderCommentFixer changed the output after successful first run with ' . $name
        );
    }
}
