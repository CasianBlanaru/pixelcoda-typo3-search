<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes',
    ])
    ->withSkip([
        __DIR__ . '/Tests/Functional/Fixtures',
        __DIR__ . '/Resources',
    ])
    ->withPhpSets(
        php81: true,
    )
    ->withSets([
        // PHP version sets
        LevelSetList::UP_TO_PHP_81,
        
        // Code quality sets
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
        SetList::CODING_STYLE,
    ])
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
        InlineConstructorDefaultToPropertyRector::class,
        RemoveUnusedPromotedPropertyRector::class,
    ])
    ->withImportNames(
        importShortClasses: true,
        removeUnusedImports: true,
    );
