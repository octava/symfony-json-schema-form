<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('deploy');

// @see https://github.com/FriendsOfPHP/PHP-CS-Fixer for rules
$config = new PhpCsFixer\Config();
return $config->setRules([
    '@Symfony' => true,
    '@PHPCompatibility' => true,
    'array_syntax' => ['syntax' => 'short'],
    'multi_line_extends_each_single_line' => true,
    'trailing_comma_in_multiline_array' => true,
])
    ->setFinder($finder->getIterator());
