<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Foundation\Console\Command;

use SebastianBergmann\Environment\Console;

final class Info
{
    /**
     * @var Console|null
     */
    private ?Console $env = null;

    public function __construct()
    {
        if (\class_exists(Console::class)) {
            $this->env = new Console();
        }
    }

    /**
     * @return positive-int
     */
    public function getSize(): int
    {
        return $this->env?->getNumberOfColumns() ?? 80;
    }

    /**
     * @param string ...$messages
     * @return int
     */
    public function getCalculatedSize(string ...$messages): int
    {
        $size = $full = $this->getSize();

        foreach ($messages as $message) {
            $size -= \mb_strlen(\strip_tags($message));
        }

        if ($size < 0) {
            $size = $full;
        }

        return $size;
    }

    /**
     * @return bool
     */
    public function hasColors(): bool
    {
        return $this->env?->hasColorSupport() ?? false;
    }

    /**
     * @return bool
     */
    public function isInteractive(): bool
    {
        return $this->env?->isInteractive() ?? false;
    }
}
