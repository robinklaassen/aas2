<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->import(SetList::COMMON);
    $config->import(SetList::PSR_12);
    $config->import(SetList::CLEAN_CODE);

    // need more fixers? Search them on: https://mlocati.github.io/php-cs-fixer-configurator
    $services = $config->services();
    $services->set(PhpCsFixer\Fixer\Comment\SingleLineCommentStyleFixer::class);
    $services->set(PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer::class);
    $services->set(PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer::class);
    $services->set(PhpCsFixer\Fixer\Alias\ArrayPushFixer::class);

    $parameters = $config->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/app/',
        __DIR__ . '/config/',
        __DIR__ . '/database/',
        __DIR__ . '/routes/',
        __DIR__ . '/tests/',
        __DIR__ . '/public/index.php',
        __DIR__ . '/ecs.php',
    ]);

    $parameters->set(Option::SKIP, [
        PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer::class,
    ]);
};
