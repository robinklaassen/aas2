<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->sets([
        SetList::COMMON,
        SetList::PSR_12,
        SetList::CLEAN_CODE,
    ]);

    // need more fixers? Search them on: https://mlocati.github.io/php-cs-fixer-configurator
    $config->rules([
        PhpCsFixer\Fixer\Comment\SingleLineCommentStyleFixer::class,
        PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer::class,
        PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer::class,
        PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer::class,
        PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer::class,
        PhpCsFixer\Fixer\Alias\ArrayPushFixer::class,
    ]);

    $config->paths([
        __DIR__ . '/app/',
        __DIR__ . '/config/',
        __DIR__ . '/database/',
        __DIR__ . '/routes/',
        __DIR__ . '/tests/',
        __DIR__ . '/public/index.php',
        __DIR__ . '/ecs.php',
    ]);

    $config->skip([
        PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer::class,
    ]);
};
