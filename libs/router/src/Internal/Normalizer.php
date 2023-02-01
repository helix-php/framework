<?php

declare(strict_types=1);

namespace Helix\Router\Internal;

/**
 * @internal Normalizer is an internal library class, please do not use it in your code.
 * @psalm-internal Helix\Router
 */
final class Normalizer
{
    /**
     * @param array<string> $chunks
     * @param bool $concat
     * @return string
     */
    public static function chunks(array $chunks, bool $concat = false): string
    {
        $result = '';

        foreach ($chunks as $chunk) {
            // Skip empty strings
            if (!\trim($chunk)) {
                continue;
            }

            /** @psalm-suppress ArgumentTypeCoercion */
            $result .= $concat ? \ltrim(self::path($chunk), '/') : self::path($chunk);
        }

        return Normalizer::path($result);
    }

    /**
     * @param string $path
     * @param bool $atRoot
     * @return string
     */
    public static function path(string $path, bool $atRoot = true): string
    {
        $path = \trim($path, " \t\n\r\0\x0B/");

        while (true) {
            $result = \str_replace('//', '/', $path);

            if ($result === $path) {
                break;
            }

            $path = $result;
        }

        return ($atRoot ? '/' : '') . $path;
    }
}
