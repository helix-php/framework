<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\AddTagToChangelogReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;

return static function (MBConfig $config): void {
    $config->packageAliasFormat('<major>.<minor>.x-dev');
    $config->defaultBranch('master');

    $config->packageDirectories([
        __DIR__ . '/libs/contracts',
        __DIR__ . '/libs',
    ]);

    $config->dataToAppend([
        'require-dev' => [
            'friendsofphp/php-cs-fixer'     => '^3.35',
            'symplify/monorepo-builder'     => '^11.2',
            'symfony/var-dumper'            => '^5.4|^6.0',
            'phpunit/phpunit'               => '^10.4',
            'vimeo/psalm'                   => '^5.15',
            'jetbrains/phpstorm-attributes' => '^1.0',

            // Status Codes Implementation
            'symfony/http-foundation'       => '^5.4|^6.0',

            // PSR-7/PSR-17 implementations
            'nyholm/psr7'                   => '^1.5',
            'laminas/laminas-diactoros'     => '^2.12',
            'httpsoft/http-message'         => '^1.0',
        ],
    ]);

    $config->workers([
        UpdateReplaceReleaseWorker::class,
        SetCurrentMutualDependenciesReleaseWorker::class,
        AddTagToChangelogReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
        SetNextMutualDependenciesReleaseWorker::class,
        UpdateBranchAliasReleaseWorker::class,
        PushNextDevReleaseWorker::class,
    ]);
};
