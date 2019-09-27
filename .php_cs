<?php

$rules = [
    '@Symfony'               => true,
    'binary_operator_spaces' => [
        'operators' => [
            '=>' => 'align',
        ],
    ],
    'cast_spaces'            => 'none',
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setUsingCache(true);
