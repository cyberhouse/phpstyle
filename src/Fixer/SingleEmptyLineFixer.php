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
use Symfony\CS\Tokenizer\Tokens;

/**
 * Ensures there a no consecutive empty lines
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class SingleEmptyLineFixer extends AbstractFixer
{
    public function getLevel()
    {
        return FixerInterface::PSR2_LEVEL;
    }

    public function fix(\SplFileInfo $file, $content)
    {
        $tokens  = Tokens::fromCode($content);

        for ($i = 0; $i < $tokens->count(); $i++) {
            if ($tokens[$i]->isGivenKind(T_WHITESPACE)) {
                if (strpos($tokens[$i]->getContent(), "\n") !== false) {
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

    public function getDescription()
    {
        return 'Single empty lines fixer';
    }
}
