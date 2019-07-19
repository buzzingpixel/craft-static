<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor'])
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules(
        [
            'mb_str_functions' => true,
        ]
    )
    ->setRiskyAllowed(true)
    ->setFinder($finder);
