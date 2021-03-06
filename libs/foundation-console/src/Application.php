<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Foundation\Console;

use Helix\Container\Exception\RegistrationException;
use Helix\Foundation\Application as BaseApplication;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;

final class Application extends BaseApplication
{
    /**
     * @var SymfonyApplication
     */
    private SymfonyApplication $cli;

    /**
     * @var bool
     */
    private bool $running = false;

    /**
     * @param CreateInfo $info
     * @throws RegistrationException
     */
    public function __construct(CreateInfo $info)
    {
        parent::__construct($info);

        $this->boot();
    }

    /**
     * @param Command|class-string<Command> ...$commands
     * @return void
     */
    public function add(Command|string ...$commands): void
    {
        if ($this->running) {
            throw new \LogicException('Can not add command to running CLI application');
        }

        foreach ($commands as $command) {
            if (\is_string($command)) {
                $command = $this->container->make($command);
            }

            $this->cli->add($command);
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function run(): int
    {
        parent::run();

        try {
            $this->running = true;
            return $this->cli->run();
        } finally {
            $this->running = false;
        }
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        $version = $this->version;

        if (\ctype_digit($version[0])) {
            $version = 'v' . $version;
        }

        return \sprintf('Helix Framework (%s)', $version);
    }

    /**
     * @return void
     */
    private function boot(): void
    {
        $this->cli = new SymfonyApplication($this->getName());

        $this->container->instance($this->cli);
    }
}
