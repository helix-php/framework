<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Foundation\Console;

use Helix\Contracts\Container\DispatcherInterface;
use Helix\Foundation\Console\Command\Info;
use Helix\Foundation\Console\Command\InteractsWithInputTrait;
use Helix\Foundation\Console\Command\InteractsWithOutputTrait;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method void invoke(mixed ...$args)
 */
abstract class Command extends SymfonyCommand
{
    use InteractsWithInputTrait;
    use InteractsWithOutputTrait;

    /**
     * @var InputInterface
     */
    protected readonly InputInterface $input;

    /**
     * @var OutputInterface
     */
    protected readonly OutputInterface $output;

    /**
     * The console command name.
     *
     * @var non-empty-string
     */
    protected string $name = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = '';

    /**
     * The console command help text.
     *
     * @var string
     */
    protected string $help = '';

    /**
     * Indicates whether the command should be shown in the commands list.
     *
     * @var bool
     */
    protected bool $hidden = false;

    /**
     * @var Info
     */
    private readonly Info $info;

    /**
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly DispatcherInterface $dispatcher,
    ) {
        parent::__construct();

        $this->info = new Info();
    }

    /**
     * @return InputInterface
     */
    protected function getInput(): InputInterface
    {
        return $this->input;
    }

    /**
     * @return OutputInterface
     */
    protected function getOutput(): OutputInterface
    {
        return $this->output;
    }

    /**
     * @return int
     */
    protected function getSize(): int
    {
        return $this->info->getSize();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        if (!\method_exists($this, 'invoke')) {
            throw new \BadFunctionCallException(
                'You must define the invoke() method in the concrete command class.'
            );
        }

        $this->dispatcher->call($this->invoke(...));

        return self::SUCCESS;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName($this->name);
        $this->setDescription($this->description);
        $this->setHelp($this->help);
        $this->setHidden($this->hidden);
    }
}
