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
use Cyberhouse\Phpstyle\Fixer\NamespaceFirstFixer;
use Cyberhouse\Phpstyle\Fixer\SingleEmptyLineFixer;

/**
 * Test combined fixer calls yield the desired output
 */
class AllFixersTest extends \PHPUnit_Framework_TestCase
{
    public function testDataProvider()
    {
        $variations = ['', 'NoNs'];
        $res  = [];

        foreach ($variations as $variation) {
            $sets = [
                'WrongOrder' . $variation,
                'NoComment' . $variation,
            ];
            $dir  = __DIR__ . '/../Fixtures/Combined/';
            $exp  = file_get_contents($dir . 'Expected' . $variation . '.php');

            foreach ($sets as $set) {
                $res[] = [
                    file_get_contents($dir . $set . '.php'),
                    $exp,
                    $set,
                ];
            }
        }

        return $res;
    }

    /**
     * @dataProvider testDataProvider
     * @param string $data
     * @param string $expected
     * @param string $set
     */
    public function testConsecutiveFixerCalls($data, $expected, $set)
    {
        $header = <<<EOF
            This file is (c) 2016 by Cyberhouse GmbH

            It is free software; you can redistribute it and/or
            modify it under the terms of the Apache License 2.0

            For the full copyright and license information see
            <http://www.apache.org/licenses/LICENSE-2.0>
EOF;
        $file  = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        foreach (get_class_methods(\SplFileInfo::class) as $method) {
            $file->expects($this->never())->method($method);
        }

        LowerHeaderCommentFixer::setHeader($header);

        $actual = $data;
        $fixers = [
            new LowerHeaderCommentFixer(),
            new NamespaceFirstFixer(),
            new SingleEmptyLineFixer(),
        ];

        foreach ($fixers as $fixer) {
            $actual = $fixer->fix($file, $actual);
        }

        $this->assertSame($expected, $actual, 'Unexpected result for data set ' . $set);
    }
}
