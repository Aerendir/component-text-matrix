<?php

declare(strict_types=1);

/*
 * This file is part of the Serendipity HQ Text Matrix Component.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Rector\Config\RectorConfig;
use SerendipityHQ\Integration\Rector\SerendipityHQ;

$allowedRunPaths = [
    // From inside Docker
    '/project',
    '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin',

    // ON GitHub Actions
    '/home/runner/.composer/vendor/bin',
];

$canRun = false;
foreach ($allowedRunPaths as $allowedRunPath) {
    if (str_starts_with($_SERVER['PATH'] ?? '', $allowedRunPath)) {
        $canRun = true;
    }
}

if (false === $canRun) {
    $message = <<<EOF
        It seems you are running `composer fix` from outside the development container, maybe from your host machine.
        Please, run it from inside the container (`make start && make sh`).
        EOF;

    throw new RuntimeException(sprintf("%s\n\nCurrent path:\n%s\n\nAllowed paths:\n%s", $message, $_SERVER['PATH'], implode(', ', $allowedRunPaths)));
}

$toSkip = SerendipityHQ::buildToSkip(SerendipityHQ::SHQ_LIBRARY_SKIP);

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    // This causes issues with controllers
    // Until required for tests, keep it commented
    ->withBootstrapFiles([__DIR__ . '/vendor-bin/phpunit/vendor/autoload.php'])
    ->withSets([
        Rector\Set\ValueObject\SetList::CODE_QUALITY,
        Rector\Set\ValueObject\SetList::CODING_STYLE,
        Rector\Set\ValueObject\SetList::TYPE_DECLARATION,
    ])
    ->withRules([
        Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector::class,
        Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector::class,
        Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector::class,
    ])
    ->withImportNames(importNames: true, importDocBlockNames: true, importShortClasses: false)
    ->withSkip($toSkip)
    ->withCache(
        './var/cache/rector',
        Rector\Caching\ValueObject\Storage\FileCacheStorage::class
    )
    ->withImportNames(importNames: true, importDocBlockNames: true, importShortClasses: false)
    ->withComposerBased(phpunit: true);
