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

use Cyberhouse\Phpstyle\Fixer\SingleEmptyLineFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;

/**
 * Test the single empty line fixer
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class SingleEmptyLineFixerTest extends TestCase
{
    public function singleEmptyLineData()
    {
        $result = [];
        $sets = 2;
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                  'Fixtures' . DIRECTORY_SEPARATOR . 'SingleEmptyLineFixer' . DIRECTORY_SEPARATOR;

        for ($i = 1; $i <= $sets; $i++) {
            $name = str_pad((string) $i, 3, '0', STR_PAD_LEFT);
            $result[] = [
                $name,
                file_get_contents($dir . $name . 'Src.php'),
                file_get_contents($dir . $name . 'Expected.php'),
            ];
        }

        return $result;
    }

    /**
     * @dataProvider singleEmptyLineData
     * @param string $set
     * @param string $src
     * @param string $expected
     */
    public function testSingleEmptyLine($set, $src, $expected)
    {
        $file = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        $actual = Tokens::fromCode($src);
        $fixer = new SingleEmptyLineFixer();
        $fixer->fix($file, $actual);

        $this->assertSame($expected, $actual->generateCode(), 'Invalid output for data set ' . $set);
    }
}
