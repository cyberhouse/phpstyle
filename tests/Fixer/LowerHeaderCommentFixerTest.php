<?php
namespace Cyberhouse\Phpstyle\Tests\Fixer;

/*
 * This file is (c) 2017 by Cyberhouse GmbH
 *
 * It is free software; you can redistribute it and/or
 * modify it under the terms of the MIT License (MIT)
 *
 * For the full copyright and license information see
 * <https://opensource.org/licenses/MIT>
 */

use Cyberhouse\Phpstyle\Fixer\LowerHeaderCommentFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;

/**
 * Test the LowerHeaderComment fixer
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class LowerHeaderCommentFixerTest extends TestCase
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
        $res = [];
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                'Fixtures' . DIRECTORY_SEPARATOR . 'HeaderCommentFixer' . DIRECTORY_SEPARATOR;

        $exp = file_get_contents($dir . 'Expected.php');

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
        $file = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        LowerHeaderCommentFixer::setHeader($header);

        $expected = Tokens::fromCode($expected);
        $actual = Tokens::fromCode($src);

        $fixer->fix($file, $actual);

        $this->assertSame(
            $expected->generateCode(),
            $actual->generateCode(),
            'HeaderCommentFixer failed with data set ' . $name
        );

        $fixer->fix($file, $actual);

        $this->assertSame(
            $expected->generateCode(),
            $actual->generateCode(),
            'HeaderCommentFixer changed the output after successful first run with ' . $name
        );
    }
}
