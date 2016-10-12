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
 * Fixer to ensure the scalar type hints are enforced
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class StrictTypesFixer extends AbstractFixer
{
    public function getLevel()
    {
        return FixerInterface::PSR2_LEVEL;
    }

    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        $startAt = -1;

        foreach ($tokens as $i => $token) {
            if (trim($token->getContent()) == '<?php') {
                $token->setContent("<?php\n");
                $startAt = $i;
            }

            if (trim($token->getContent()) == 'declare' && $token->isGivenKind(336)) {
                do {
                    $content = $tokens[$i]->getContent();
                    $tokens[$i]->clear();
                    $i++;
                } while ($content != ';');

                if ($tokens[$i]->isGivenKind(T_WHITESPACE)) {
                    $content = $tokens[$i]->getContent();

                    if (strlen($content) < 2) {
                        $tokens[$i]->clear();
                    } else {
                        $tokens[$i]->setContent(substr($content, 1));
                    }
                }

                break;
            }
        }

        $tokens->clearEmptyTokens();
        $tokens->insertAt($startAt + 1, [
            new Token([336, 'declare']),
            new Token('('),
            new Token([319, 'strict_types']),
            new Token('='),
            new Token([317, '1']),
            new Token(')'),
            new Token(';'),
            new Token([382, "\n"]),
        ]);

        return $tokens->generateCode();
    }

    public function getDescription()
    {
        return 'Ensure a declare for strict types at the beginning to the file';
    }
}
