<?php
namespace Cyberhouse\Phpstyle\Fixer;

/*
 * (c) 2017 by Cyberhouse GmbH
 *
 * This is free software; you can redistribute it and/or
 * modify it under the terms of the MIT License (MIT)
 *
 * For the full copyright and license information see
 * <https://opensource.org/licenses/MIT>
 */

use PhpCsFixer\Tokenizer\Tokens;

/**
 * Ensures there a no consecutive empty lines
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class SingleEmptyLineFixer extends BaseFixer
{
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        for ($i = 0; $i < $tokens->count(); $i++) {
            if ($tokens[$i]->isGivenKind(T_WHITESPACE)) {
                if (mb_strpos($tokens[$i]->getContent(), "\n") !== false) {
                    $content = explode("\n", $tokens[$i]->getContent());

                    if (count($content) > 3) {
                        $content = array_slice($content, -3);
                    }

                    $tokens[$i]->setContent(implode("\n", $content));
                }
            }
        }

        $tokens->clearEmptyTokens();
        return $tokens->generateCode();
    }
}
