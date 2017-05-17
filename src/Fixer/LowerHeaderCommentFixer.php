<?php
namespace Cyberhouse\Phpstyle\Fixer;

/*
 * This file is (c) 2017 by Cyberhouse GmbH
 *
 * It is free software; you can redistribute it and/or
 * modify it under the terms of the MIT License (MIT)
 *
 * For the full copyright and license information see
 * <https://opensource.org/licenses/MIT>
 */

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * A php-cs-fixer fixer implementation that uses a slightly
 * different layout than the default header comment fixer
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class LowerHeaderCommentFixer extends BaseFixer
{
    private static $headerComment = '';

    public static function setHeader($header)
    {
        $header = trim((string) $header);

        if (!empty($header)) {
            self::$headerComment = "/*\n";

            foreach (explode("\n", $header) as $line) {
                self::$headerComment .= rtrim(' * ' . trim($line)) . "\n";
            }

            self::$headerComment .= ' */';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return self::$headerComment !== '' && $tokens->isMonolithicPhp();
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        $index = 1;
        $hasNs = false;

        for ($i = 1; $i < $tokens->count(); $i++) {
            if ($tokens[$i]->isGivenKind(T_NAMESPACE)) {
                $hasNs = true;

                while ($tokens[$i]->getContent() !== ';') {
                    $i++;
                }
                $i++;
                $index = $i + 1;
            } elseif (!$tokens[$i]->isWhitespace() && !$tokens[$i]->isGivenKind(T_COMMENT)) {
                break;
            }
            $tokens[$i]->clear();
        }

        $tokens->insertAt($index, [
            new Token([T_WHITESPACE, "\n" . ($hasNs ? "\n" : '')]),
            new Token([T_COMMENT, self::$headerComment]),
            new Token([T_WHITESPACE, "\n\n"]),
        ]);

        $tokens->clearEmptyTokens();
    }
}
