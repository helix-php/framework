<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Foundation\Console\Command;

use Symfony\Component\Console\Input\InputInterface;

trait InteractsWithInputTrait
{
    /**
     * @return InputInterface
     */
    abstract protected function getInput(): InputInterface;

    /**
     * Determine if the given argument is present.
     *
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        $input = $this->getInput();

        return $input->hasArgument($name);
    }

    /**
     * Returns the value of a command argument.
     *
     * @param string $name
     * @return mixed
     */
    public function getArgument(string $name): mixed
    {
        $input = $this->getInput();

        return $input->getArgument($name);
    }

    /**
     * Returns list of a command arguments.
     *
     * @return iterable<non-empty-string, mixed>
     */
    public function getArguments(): iterable
    {
        $input = $this->getInput();

        return $input->getArguments();
    }

    /**
     * Determine if the given option is present.
     *
     * @param string $name
     * @return bool
     */
    public function hasOption(string $name): bool
    {
        $input = $this->getInput();

        return $input->hasOption($name);
    }

    /**
     * Returns the value of a command option.
     *
     * @param string $name
     * @return mixed
     */
    public function getOption(string $name): mixed
    {
        $input = $this->getInput();

        return $input->getOption($name);
    }

    /**
     * Returns list of a command options.
     *
     * @return iterable<non-empty-string, mixed>
     */
    public function getOptions(): iterable
    {
        $input = $this->getInput();

        return $input->getOptions();
    }
}
