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

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * Base for custom fixers
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 */
abstract class BaseFixer extends AbstractFixer
{
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isMonolithicPhp();
    }

    public function getName()
    {
        return 'Cyberhouse/' . parent::getName();
    }
}
