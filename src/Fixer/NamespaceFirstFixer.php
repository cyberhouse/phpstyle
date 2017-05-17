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

use PhpCsFixer\Tokenizer\Tokens;

/**
 * Fixer to ensure the namespace comes as first element
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class NamespaceFirstFixer extends BaseFixer
{
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        $ns = null;
        $prev = null;

        foreach ($tokens as $i => $token) {
            if ($token->isGivenKind(T_NAMESPACE)) {
                $ns = [clone $token];
                $token->clear();
            } elseif (is_array($ns)) {
                $new = clone $token;
                $ns[] = $new;

                $token->clear();

                if (trim($new->getContent()) === ';') {
                    break;
                }
            }
        }

        if (is_array($ns)) {
            $tokens->clearEmptyTokens();
            $tokens->insertAt(1, $ns);
        }
    }
}
