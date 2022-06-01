<?php

/**
 * This file is part of Helix package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Helix\ParamResolver\Parameter;

/**
 * @internal Helix\ParamResolver\Parameter\Printer is an internal library class,
 *           please do not use it in your code.
 * @psalm-internal Helix\ParamResolver
 */
final class Printer
{
    /**
     * @var non-empty-string
     */
    private const ERROR_NOT_SUPPORTED = '%s not supported yet';

    /**
     * @param \ReflectionFunction $fun
     * @return string
     */
    private static function functionToString(\ReflectionFunction $fun): string
    {
        if ($fun->isClosure()) {
            return \Closure::class;
        }

        return $fun->getName();
    }

    /**
     * @param \ReflectionMethod $method
     * @return string
     */
    private static function methodToString(\ReflectionMethod $method): string
    {
        $class = $method->getDeclaringClass();
        $access = $method->isStatic() ? '::' : '->';

        if ($class->isAnonymous()) {
            // Note: Anonymous classes contain "\0" in the name.
            return 'class@anonymous' . $access . $method->getName();
        }

        return $class->getName() . $access . $method->getName();
    }

    /**
     * @param \ReflectionParameter $param
     * @return string
     */
    public static function printParameterContext(\ReflectionParameter $param): string
    {
        $function = $param->getDeclaringFunction();

        return match (true) {
            $function instanceof \ReflectionFunction => self::functionToString($function),
            $function instanceof \ReflectionMethod => self::methodToString($function),
            default => throw new \InvalidArgumentException(
                \sprintf(self::ERROR_NOT_SUPPORTED, $function::class)
            ),
        };
    }

    /**
     * @param \ReflectionParameter $param
     * @return string
     */
    public static function printParameter(\ReflectionParameter $param): string
    {
        $result = '';

        if ($param->isPromoted()) {
            $result .= '[promoted] ';
        }

        $result .= self::printType($param->getType()) . ' ';

        if ($param->isVariadic()) {
            $result .= '...';
        }

        if ($param->isPassedByReference()) {
            $result .= '&';
        }

        $result .= '$' . $param->getName();

        if ($param->isDefaultValueAvailable()) {
            /** @psalm-suppress PossiblyNullOperand */
            $result .= ' = ' . self::getDefaultValue($param);
        }

        return $result;
    }

    /**
     * @param \ReflectionParameter $param
     * @return string|null
     */
    private static function getDefaultValue(\ReflectionParameter $param): ?string
    {
        if ($param->isDefaultValueAvailable()) {
            $default = $param->getDefaultValue();

            return \is_scalar($default) ? (string)$default : \var_export($default);
        }

        return null;
    }

    /**
     * @param \ReflectionType|null $type
     * @return string
     */
    private static function printType(?\ReflectionType $type): string
    {
        if ($type === null) {
            return 'mixed';
        }

        return self::typeToString($type, 0);
    }

    /**
     * @param \ReflectionType $type
     * @param int $depth
     * @return string
     */
    private static function typeToString(\ReflectionType $type, int $depth): string
    {
        $result = match (true) {
            $type instanceof \ReflectionNamedType =>
                self::namedTypeToString($type),
            $type instanceof \ReflectionIntersectionType =>
                self::intersectionTypeToString($type, $depth),
            $type instanceof \ReflectionUnionType =>
                self::unionTypeToString($type, $depth),
            default => throw new \InvalidArgumentException(
                \sprintf(self::ERROR_NOT_SUPPORTED, $type::class)
            ),
        };

        return $depth > 0 ? "($result)" : $result;
    }

    /**
     * @param \ReflectionUnionType $type
     * @param int $depth
     * @return string
     */
    private static function unionTypeToString(\ReflectionUnionType $type, int $depth): string
    {
        $result = [];

        foreach ($type->getTypes() as $child) {
            $result[] = self::typeToString($child, $depth + 1);
        }

        return \implode('|', $result);
    }

    /**
     * @param \ReflectionIntersectionType $type
     * @param int $depth
     * @return string
     */
    private static function intersectionTypeToString(\ReflectionIntersectionType $type, int $depth): string
    {
        $result = [];

        foreach ($type->getTypes() as $child) {
            $result[] = self::typeToString($child, $depth + 1);
        }

        return \implode('&', $result);
    }

    /**
     * @param \ReflectionNamedType $type
     * @return string
     */
    private static function namedTypeToString(\ReflectionNamedType $type): string
    {
        return $type->getName();
    }
}
