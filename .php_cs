<?php

$rules = [
    '@Symfony'               => true,
    'no_superfluous_phpdoc_tags' => false,
    'binary_operator_spaces' => [
        'operators' => [
            '=>' => 'align',
        ],
    ],
    'cast_spaces'            => ['space' => 'none'],
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setUsingCache(true);
