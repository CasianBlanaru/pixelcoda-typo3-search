<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes',
        __DIR__ . '/Configuration',
        __DIR__ . '/Tests',
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
        
        // TYPO3 specific sets
        Typo3LevelSetList::UP_TO_TYPO3_12,
        Typo3SetList::TYPO3_12,
        Typo3SetList::DATABASE_TO_DBAL,
        Typo3SetList::EXTBASE_CODE_QUALITY,
    ])
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
        InlineConstructorDefaultToPropertyRector::class,
        RemoveUnusedPromotedPropertyRector::class,
        RestoreDefaultNullToNullableTypePropertyRector::class,
    ])
    ->withImportNames(
        importShortClasses: true,
        removeUnusedImports: true,
    )
    ->withParallel(
        timeoutSeconds: 360,
        maxNumberOfProcess: 16,
    );
