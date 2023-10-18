<?php

declare(strict_types=1);

namespace Helix\Http\StatusCode;

use Helix\Contracts\Http\StatusCode\CategoryInterface;

/**
 * @internal Helix\Http\StatusCode\Info is an internal library class, please do not use it in your code.
 * @psalm-internal Helix\Http\StatusCode
 */
#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
final class Info
{
    /**
     * @param string $reasonPhrase
     * @param CategoryInterface $category
     */
    public function __construct(
        public readonly string $reasonPhrase = '',
        public readonly CategoryInterface $category = Category::UNKNOWN,
    ) {}
}
