<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Foundation\Console\Command;

use Helix\Foundation\Console\Helper\Item;
use Helix\Foundation\Console\Helper\TreeItem;
use Helix\Foundation\Console\Helper\TreeItem\Location;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\OutputInterface;

trait InteractsWithOutputTrait
{
    /**
     * Format input to textual table.
     *
     * @param array $headers
     * @param array $rows
     * @param TableStyle|string $tableStyle
     * @param array $columnStyles
     * @return void
     */
    public function table(
        array $headers,
        array $rows,
        string|TableStyle $tableStyle = 'default',
        array $columnStyles = []
    ): void {
        $table = (new Table($this->output))
            ->setHeaders($headers)
            ->setRows($rows)
            ->setStyle($tableStyle);

        foreach ($columnStyles as $columnIndex => $columnStyle) {
            $table->setColumnStyle($columnIndex, $columnStyle);
        }

        $table->render();
    }

    /**
     * @return OutputInterface
     */
    abstract protected function getOutput(): OutputInterface;

    /**
     * @return int
     */
    abstract protected function getSize(): int;

    /**
     * Writes a message to the output.
     *
     * @param string $message
     * @return void
     */
    protected function write(string $message): void
    {
        $this->output->write($message);
    }

    /**
     * Writes eol (new line) to the output.
     *
     * @param positive-int $count
     * @return void
     */
    protected function eol(int $count = 1): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $this->output->writeln('');
        }
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string $message
     * @return void
     */
    protected function writeln(string $message): void
    {
        $this->output->writeln($message);
    }

    /**
     * Write a string as information output.
     *
     * @param string $message
     * @return void
     */
    protected function info(string $message): void
    {
        $this->writeln($this->getInfoString($message));
    }

    /**
     * @param string $message
     * @return non-empty-string
     */
    protected function getInfoString(string $message): string
    {
        return \sprintf('<info>%s</info>', $message);
    }

    /**
     * Write a string as comment output.
     *
     * @param string $message
     * @return void
     */
    protected function comment(string $message): void
    {
        $this->writeln($this->getCommentString($message));
    }

    /**
     * @param string $message
     * @return non-empty-string
     */
    protected function getCommentString(string $message): string
    {
        return \sprintf('<comment>%s</comment>', $message);
    }

    /**
     * Write a string as question output.
     *
     * @param string $message
     * @return void
     */
    protected function question(string $message): void
    {
        $this->writeln($this->getQuestionString($message));
    }

    /**
     * @param string $message
     * @return non-empty-string
     */
    protected function getQuestionString(string $message): string
    {
        return \sprintf('<question>%s</question>', $message);
    }

    /**
     * Write a string as error output.
     *
     * @param string $message
     * @return void
     */
    protected function error(string $message): void
    {
        $this->writeln($this->getErrorString($message));
    }

    /**
     * @param string $message
     * @return non-empty-string
     */
    protected function getErrorString(string $message): string
    {
        return \sprintf('<error>%s</error>', $message);
    }

    /**
     * Write a string as warning output.
     *
     * @param string $message
     * @return void
     */
    protected function warning(string $message): void
    {
        $this->writeln($this->getWarningString($message));
    }

    /**
     * @param string $message
     * @return non-empty-string
     */
    protected function getWarningString(string $message): string
    {
        $formatter = $this->output->getFormatter();

        if (!$formatter->hasStyle('warning')) {
            $formatter->setStyle('warning', new OutputFormatterStyle('yellow'));
        }

        return \sprintf('<warning>%s</warning>', $message);
    }

    /**
     * Write a string as description output.
     *
     * @param string $message
     * @return void
     */
    protected function description(string $message): void
    {
        $this->writeln($this->getDescriptionString($message));
    }

    /**
     * @param string $message
     * @return non-empty-string
     */
    protected function getDescriptionString(string $message): string
    {
        $formatter = $this->output->getFormatter();

        if (!$formatter->hasStyle('description')) {
            $formatter->setStyle('description', new OutputFormatterStyle('gray'));
        }

        return \sprintf('<description>%s</description>', $message);
    }

    /**
     * Writes separator line to the output.
     *
     * @param positive-int|null $size
     * @param non-empty-string $char
     * @return void
     */
    protected function separator(int $size = null, string $char = '─'): void
    {
        $this->writeln($this->getLineString($size ?? $this->getSize(), $char));
    }

    /**
     * Writes a simple line to the output.
     *
     * @param positive-int|null $size
     * @param non-empty-string $char
     * @return void
     */
    protected function line(int $size = null, string $char = '┄'): void
    {
        $this->write($this->getLineString($size ?? $this->getSize(), $char));
    }

    /**
     * @param int $size
     * @param non-empty-string $char
     * @return non-empty-string
     */
    protected function getLineString(int $size, string $char = '─'): string
    {
        return \sprintf('<fg=gray>%s</>', \str_repeat($char, $size));
    }

    /**
     * Writes a menu item to the output.
     *
     * @param string $prefix
     * @param string $suffix
     * @param non-empty-string $char
     * @return void
     */
    protected function item(string $prefix = '', string $suffix = '', string $char = '┄'): void
    {
        $item = new Item($prefix, $suffix);
        $item->setSeparator($char);

        $item->render($this->output);
    }

    /**
     * @param string $title
     * @param string $suffix
     * @return TreeItem
     */
    protected function tree(string $title, string $suffix = ''): TreeItem
    {
        return new TreeItem($title, $suffix);
    }

    /**
     * @param int $max
     * @return ProgressBar
     */
    protected function progress(int $max = 0): ProgressBar
    {
        return new ProgressBar($this->output, $max);
    }
}
