<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $config): void {
    $config->packageDirectories([
        __DIR__ . '/libs/contracts',
        __DIR__ . '/libs',
    ]);

    $config->dataToAppend([
        'description' => 'Helix Framework',
        'homepage' => 'https://github.com/helix-php',
        'prefer-stable' => true,
        'minimum-stability' => 'dev',
        'type' => 'library',
        'license' => 'MIT',
        'support' => [
            'issues' => 'https://github.com/helix-php/framework/issues',
            'source' => 'https://github.com/helix-php/framework',
        ],
        'authors' => [
            [
                'name'      => 'Kirill Nesmeyanov',
                'email'     => 'nesk@xakep.ru',
                'role'      => 'maintainer',
                'homepage'  => 'https://nesk.me'
            ],
        ],
        'require-dev' => [
            'symplify/monorepo-builder'     => '^11.2',
            'symfony/var-dumper'            => '^5.4|^6.0',
            'phpunit/phpunit'               => '^9.5.20',
            'vimeo/psalm'                   => '^5.6',
            'jetbrains/phpstorm-attributes' => '^1.0',
            'squizlabs/php_codesniffer'     => '^3.7',

            // Status Codes Implementation
            'symfony/http-foundation'       => '^5.4|^6.0',

            // PSR-7/PSR-17 implementations
            'nyholm/psr7'                   => '^1.5',
            'laminas/laminas-diactoros'     => '^2.12',
            'httpsoft/http-message'         => '^1.0',
        ],
    ]);
};
