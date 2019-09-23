<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('src')
    ->exclude('tests');

return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
