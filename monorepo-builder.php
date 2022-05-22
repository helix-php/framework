<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        __DIR__ . '/libs',
    ]);

    $parameters->set(Option::DATA_TO_APPEND, [
        ComposerJsonSection::DESCRIPTION => 'Helix Framework',
        ComposerJsonSection::HOMEPAGE => 'https://github.com/helix-php',
        ComposerJsonSection::PREFER_STABLE => true,
        ComposerJsonSection::MINIMUM_STABILITY => 'dev',
        ComposerJsonSection::TYPE => 'library',
        ComposerJsonSection::LICENSE => 'MIT',
        ComposerJsonSection::AUTHORS     => [
            [
                'name'  => 'Kirill Nesmeyanov',
                'email' => 'nesk@xakep.ru',
                'role'  => 'maintainer',
            ],
        ],
        ComposerJsonSection::REQUIRE_DEV => [
            'symplify/monorepo-builder'     => '^10.0',
            'symfony/var-dumper'            => '^5.4|^6.0',
            'phpunit/phpunit'               => '^9.5.20',
            'vimeo/psalm'                   => '^4.22',
            'jetbrains/phpstorm-attributes' => '^1.0',
            'friendsofphp/php-cs-fixer'     => '^3.8',
            'nyholm/psr7'                   => '^1.5',
            'symfony/http-foundation'       => '^5.4|^6.0',
            'laminas/laminas-diactoros'     => '^2.12',
            'httpsoft/http-message'         => '^1.0',
        ],
    ]);
};
