<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Foundation;

use Composer\InstalledVersions;
use Helix\Boot\Loader;
use Helix\Boot\LoaderInterface;
use Helix\Boot\RepositoryInterface;
use Helix\Container\Container;
use Helix\Container\Exception\RegistrationException;
use Helix\Container\Exception\ServiceNotFoundException;
use Helix\Container\Exception\ServiceNotInstantiatableException;
use Helix\ParamResolver\Exception\ParamNotResolvableException;
use Helix\ParamResolver\Exception\SignatureException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class Application implements LoaderInterface
{
    /**
     * @var Container
     */
    public readonly Container $container;

    /**
     * @var bool
     */
    public readonly bool $debug;

    /**
     * @var non-empty-string
     */
    public readonly string $env;

    /**
     * @var non-empty-string
     */
    public readonly string $version;

    /**
     * @var Loader
     */
    private readonly Loader $extensions;

    /**
     * @param CreateInfo $info
     * @throws RegistrationException
     */
    public function __construct(CreateInfo $info)
    {
        $this->env = $info->env;
        $this->debug = $info->debug;

        $this->container = new Container($info->container);
        $this->extensions = new Loader(
            $this->container->getDefinitions(),
            $this->container->getDispatcher(),
            $this->container->getEventDispatcher(),
        );

        $this->version = InstalledVersions::getPrettyVersion('helix/foundation')
            ?? 'dev-master';

        $this->bindDefaults($info);
        $this->loadMany($info->extensions);
    }

    /**
     * @param non-empty-string ...$matches
     * @return bool
     */
    public function env(string ...$matches): bool
    {
        return \in_array($this->env, $matches, true);
    }

    /**
     * @param object $extension
     * @throws RegistrationException
     */
    public function load(object $extension): void
    {
        $this->extensions->load($extension);
    }

    /**
     * @return int
     */
    public function run(): int
    {
        $this->extensions->boot();

        return 0;
    }

    /**
     * @param CreateInfo $info
     * @return void
     */
    private function bindDefaults(CreateInfo $info): void
    {
        $this->container->instance($this)
            ->as(self::class);

        $this->container->instance($info->path);

        $this->container->instance(new NullLogger())
            ->as(LoggerInterface::class);

        $this->container->instance($this->extensions)
            ->as(RepositoryInterface::class)
            ->as(LoaderInterface::class);
    }

    /**
     * @param iterable<class-string|object>|iterable<class-string, bool> $extensions
     * @return void
     * @throws RegistrationException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiatableException
     * @throws ParamNotResolvableException
     * @throws SignatureException
     */
    private function loadMany(iterable $extensions): void
    {
        $instantiator = $this->container->getInstantiator();

        foreach ($extensions as $key => $extension) {
            if (\is_bool($extension)) {
                if ($extension === false) {
                    continue;
                }

                $extension = $key;
            }

            if ($extension) {
                if (\is_string($extension)) {
                    /** @psalm-suppress ArgumentTypeCoercion */
                    $extension = $instantiator->make($extension);
                }

                $this->load($extension);
            }
        }
    }
}
