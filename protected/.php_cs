<?php
$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
->setRules([
    '@Symfony' => true,
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'no_short_echo_tag' => true,
])
->setFinder($finder);
