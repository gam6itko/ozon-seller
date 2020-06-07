<?php

$rules = [
    '@Symfony'                   => true,
    'binary_operator_spaces'     => [
        'operators' => [
            '=>' => 'align',
        ],
    ]
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setUsingCache(true);
