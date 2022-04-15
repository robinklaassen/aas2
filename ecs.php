<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::COMMON);
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::CLEAN_CODE);

    // need more fixers? Search them on: https://mlocati.github.io/php-cs-fixer-configurator
    $services = $containerConfigurator->services();
    $services->set(PhpCsFixer\Fixer\Comment\SingleLineCommentStyleFixer::class);
    $services->set(PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer::class);
    $services->set(PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer::class);
    $services->set(PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer::class);
    $services->set(PhpCsFixer\Fixer\Alias\ArrayPushFixer::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__,
    ]);

    $parameters->set(Option::SKIP, [
        __DIR__ . '/vendor',
        __DIR__ . '/node_modules',
        __DIR__ . '/storage',
    ]);

    $parameters->set(Option::SKIP, [
        PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer::class,
    ]);
};
