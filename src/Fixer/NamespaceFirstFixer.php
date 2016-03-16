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
 * Fixer to ensure the namespace comes as first element
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class NamespaceFirstFixer extends AbstractFixer
{
    public function getLevel()
    {
        return FixerInterface::CONTRIB_LEVEL;
    }

    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);
        $ns     = null;
        $prev   = null;

        foreach ($tokens as $i => $token) {
            if ($token->isGivenKind(T_NAMESPACE)) {
                $ns = [clone $token];
                $token->clear();
            } elseif (is_array($ns)) {
                $new  = clone $token;
                $ns[] = $new;

                $token->clear();

                if (trim($new->getContent()) == ';') {
                    break;
                }
            }
        }

        if (is_array($ns)) {
            $tokens->clearEmptyTokens();
            $tokens->insertAt(1, $ns);
            $content = $tokens->generateCode();
        }

        return $content;
    }

    public function getDescription()
    {
        return 'Ensure namespace as first element';
    }
}
