<?php
namespace Cyberhouse\Phpstyle\Fixer;

/*
 * (c) 2016 by Cyberhouse GmbH
 *
 * This is free software; you can redistribute it and/or
 * modify it under the terms of the MIT License (MIT)
 *
 * For the full copyright and license information see
 * <https://opensource.org/licenses/MIT>
 */

use Symfony\CS\AbstractFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

/**
 * A php-cs-fixer fixer implementation that uses a slightly
 * different layout than the default header comment fixer
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class LowerHeaderCommentFixer extends AbstractFixer
{
    private static $headerComment = '';

    public static function setHeader($header)
    {
        self::$headerComment = '';
        $header              = trim((string) $header);

        if (!empty($header)) {
            self::$headerComment = "/*\n";

            foreach (explode("\n", $header) as $line) {
                self::$headerComment .= rtrim(' * ' . trim($line)) . "\n";
            }

            self::$headerComment .= ' */';
        }
    }

    public function getDescription()
    {
        return 'Like the default HeaderCommentFixer, but places it after a namespace declaration';
    }

    /**
     * The default level
     *
     * @return int
     */
    public function getLevel()
    {
        return FixerInterface::PSR2_LEVEL;
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        if (!$tokens->isMonolithicPhp()) {
            return $content;
        }

        $index = 1;

        for ($i = 1; $i < $tokens->count(); $i++) {
            if ($tokens[$i]->isGivenKind(T_NAMESPACE)) {
                while ($tokens[$i]->getContent() !== ';') {
                    $i++;
                }
                $i++;
                $index = $i+1;
            } elseif (!$tokens[$i]->isWhitespace() && !$tokens[$i]->isComment()) {
                break;
            }
            $tokens[$i]->clear();
        }

        $headCommentTokens = [
            new Token([T_WHITESPACE, "\n"]),
        ];

        if ('' !== self::$headerComment) {
            $headCommentTokens[] = new Token([T_WHITESPACE, "\n"]);
            $headCommentTokens[] = new Token([T_COMMENT, self::$headerComment]);
            $headCommentTokens[] = new Token([T_WHITESPACE, "\n\n"]);
        }

        $tokens->insertAt($index, $headCommentTokens);
        $tokens->clearEmptyTokens();

        return $tokens->generateCode();
    }
}
