<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        '@symfony:risky' => true,
        'declare_strict_types' => true,
        'blank_line_after_opening_tag' => false,
        'linebreak_after_opening_tag' => false,
        'strict_comparison' => true,
        'strict_param' => true,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'this',
        ],
        'array_syntax' => ['syntax' => 'short'],
//'non_printable_character' => true,
        'modernize_strpos' => true,
        'no_unreachable_default_argument_value' => false,
        'php_unit_method_casing' => false,
        'no_unneeded_curly_braces' => false,
//'phpdoc_summary' => false,
        'phpdoc_separation' => [],
    ])
    ->setFinder($finder)
;
