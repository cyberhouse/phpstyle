<?php

/*
 * (c) 2016 by Cyberhouse GmbH
 *
 * This is free software; you can redistribute it and/or
 * modify it under the terms of the MIT License (MIT)
 *
 * For the full copyright and license information see
 * <https://opensource.org/licenses/MIT>
 */

use Cyberhouse\Phpstyle\Fixer\LowerHeaderCommentFixer;
use Cyberhouse\Phpstyle\Fixer\NamespaceFirstFixer;
use Symfony\CS\Config\Config;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\FixerInterface;

if (PHP_SAPI !== 'cli') {
    die('Nope');
}

$header = '(c) ' . date('Y') . ' by Cyberhouse GmbH

This is free software; you can redistribute it and/or
modify it under the terms of the MIT License (MIT)

For the full copyright and license information see
<https://opensource.org/licenses/MIT>';

LowerHeaderCommentFixer::setHeader($header);

$finder = DefaultFinder::create()
    ->exclude('res')
    ->exclude('vendor')
    ->exclude('tests/Fixtures')
    ->name('/\.php$/')
    ->in(__DIR__);

return Config::create()
    ->setUsingCache(true)
    ->level(FixerInterface::PSR2_LEVEL)
    ->fixers([
        '-psr0',
        'encoding',
        'lower_header_comment',
        'namespace_first',
        'remove_leading_slash_use',
        'single_array_no_trailing_comma',
        'ereg_to_preg',
        'spaces_before_semicolon',
        'unused_use',
        'ordered_use',
        'concat_with_spaces',
        'whitespacy_lines',
        'array_element_no_space_before_comma',
        'double_arrow_multiline_whitespaces',
        'no_blank_lines_before_namespace',
        'namespace_no_leading_whitespace',
        'native_function_casing',
        'no_empty_lines_after_phpdocs',
        'multiline_array_trailing_comma',
        'spaces_cast',
        'standardize_not_equal',
        'align_double_arrow',
        'align_equals',
        'short_array_syntax',
        'single_quote',
        'extra_empty_lines',
        'hash_to_slash_comment',
        'method_argument_default_value',
        'lowercase_cast',
        'duplicate_semicolon',
        'phpdoc_no_package',
        'phpdoc_scalar',
        'phpdoc_order',
    ])
    ->addCustomFixer(new LowerHeaderCommentFixer())
    ->addCustomFixer(new NamespaceFirstFixer())
    ->finder($finder);
