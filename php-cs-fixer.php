<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('storage')
    ->exclude('public')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'                                     => true,
        'strict_param'                               => true,
        'declare_strict_types'                       => true,
        'no_unused_imports'                          => true,
        'array_syntax'                               => ['syntax' => 'short'],
        'single_quote'                               => true,
        'no_short_bool_cast'                         => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_empty_statement'                         => true,
        'trailing_comma_in_multiline'                => true,
        'binary_operator_spaces'                     => [
            'default'   => 'align_single_space_minimal',
            'operators' => [
                '=' => 'align_single_space_minimal',
            ],
        ],
        'no_extra_blank_lines'        => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_imports'             => true,
        'single_line_comment_style'   => true,
        'no_trailing_whitespace'      => true,
        'function_typehint_space'     => true,
        'lowercase_keywords'          => true,
        'native_function_casing'      => true,
        'no_leading_import_slash'     => true,
        'ordered_class_elements'      => true,
        'return_type_declaration'     => ['space_before' => 'none'],
        'single_line_after_imports'   => true,
        'braces_position'             => [
            'functions_opening_brace'         => 'same_line',
            'classes_opening_brace'           => 'same_line',
            'anonymous_classes_opening_brace' => 'same_line',
        ],
        'single_line_empty_body' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
