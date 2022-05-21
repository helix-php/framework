<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\Mime;

use Helix\Contracts\Mime\CategoryInterface;
use Helix\Contracts\Mime\TypeInterface;

/**
 * @internal Helix\Mime\CustomType is an internal library class, please do not use it in your code.
 * @psalm-internal Helix\Mime
 */
final class CustomType implements TypeInterface
{
    /**
     * @param non-empty-string $name
     * @param CategoryInterface $category
     */
    public function __construct(
        private readonly string $name,
        private readonly CategoryInterface $category,
    ) {
    }

    /**
     * @param non-empty-string $mime
     * @return TypeInterface
     */
    public static function create(string $mime): TypeInterface
    {
        $position = \strpos($mime, '/');

        $category = $position !== false
            ? Category::create(\substr($mime, 0, $position))
            : Category::EXAMPLE;

        return new self($mime, $category);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getCategory(): CategoryInterface
    {
        return $this->category;
    }
}
