<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => false,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => ['syntax' => 'short'],
        'phpdoc_to_comment' => ['ignored_tags' => ['var', 'Ignore', 'throws']],
        'nullable_type_declaration_for_default_null_value' => true,
    ])
    ->setFinder($finder);
