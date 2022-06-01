<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Foundation\Console\Helper;

use Helix\Foundation\Console\Command\Info;
use Symfony\Component\Console\Output\OutputInterface;

class Item implements \Stringable
{
    /**
     * @var string
     */
    private string $separator = '┄';

    public function __construct(
        private string $title,
        private string $suffix = '',
    ) {
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function withTitle(string $title): self
    {
        $self = clone $this;
        $self->setTitle($title);

        return $self;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     * @return void
     */
    public function setSuffix(string $suffix): void
    {
        $this->suffix = $suffix;
    }

    /**
     * @param string $suffix
     * @return $this
     */
    public function withSuffix(string $suffix): self
    {
        $self = clone $this;
        $self->setSuffix($suffix);

        return $self;
    }

    /**
     * @return string
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * @param string $char
     * @return void
     */
    public function setSeparator(string $char): void
    {
        $this->separator = $char;
    }

    /**
     * @param string $char
     * @return $this
     */
    public function withSeparator(string $char): self
    {
        $self = clone $this;
        $self->setSeparator($char);

        return $self;
    }

    /**
     * @return $this
     */
    public function withoutSeparator(): self
    {
        return $this->withSeparator('');
    }

    /**
     * @param OutputInterface $output
     * @return void
     */
    public function render(OutputInterface $output): void
    {
        $output->writeln((string)$this);
    }

    /**
     * @return string
     */
    protected function getFormattedTitleString(): string
    {
        $title = $this->title;

        if ($title !== '') {
            $title = \rtrim($title);

            if ($this->separator !== '') {
                $title .= ' ';
            }
        }

        return $title;
    }

    /**
     * @return string
     */
    protected function getFormattedSuffixString(): string
    {
        $suffix = $this->suffix;

        if ($suffix !== '') {
            $suffix = \ltrim($suffix);

            if ($this->separator !== '') {
                $suffix = ' ' . $suffix;
            }
        }

        return $suffix;
    }

    /**
     * @param string ...$messages
     * @return string
     */
    protected function getFormattedSeparatorString(string ...$messages): string
    {
        $size = (new Info())->getCalculatedSize(...$messages);

        return \sprintf('<fg=gray>%s</>', \str_repeat($this->separator, $size));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $title = $this->getFormattedTitleString();
        $suffix = $this->getFormattedSuffixString();
        $separator = $this->getFormattedSeparatorString($title, $suffix);

        return $title . $separator . $suffix;
    }
}
