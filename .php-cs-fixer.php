<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS' => true,
        'declare_strict_types' => true,
        'no_unused_imports' => true,
    ])
    ->setFinder(
        (new Finder())
            ->in([__DIR__ . '/app', __DIR__ . '/bootstrap', __DIR__ . '/config', __DIR__ . '/tests'])
            ->append([__FILE__]),
    );
