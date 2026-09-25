<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/spec')
    ->in(__DIR__ . '/tests')
    ->append([__FILE__]);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS'                    => true,
        '@Symfony'                   => true,
        '@Symfony:risky'             => true,
        '@PHP82Migration'            => true,
        '@PHP82Migration:risky'      => true,
        'binary_operator_spaces'     => ['operators' => ['=>' => 'align_single_space_minimal', '=' => 'align_single_space_minimal']],
        'concat_space'               => ['spacing' => 'one'],
        'global_namespace_import'    => ['import_classes' => true, 'import_constants' => false, 'import_functions' => false],
        'mb_str_functions'           => true,
        'native_constant_invocation' => false,
        'native_function_invocation' => false,
        'ordered_class_elements'     => true,
        'phpdoc_var_without_name'    => false,
        'self_accessor'              => false,
    ])
    ->setFinder($finder);
