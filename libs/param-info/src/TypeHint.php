<?php

declare(strict_types=1);

namespace Helix\ParamInfo;

interface TypeHint
{
    public const INT = 'int';
    public const FLOAT = 'float';
    public const STRING = 'string';
    public const BOOL = 'bool';
    public const CALLABLE = 'callable';
    public const ARRAY = 'array';
    public const SELF = 'self';
    public const PARENT = 'parent';

    /**
     * @since PHP 7.1
     */
    public const ITERABLE = 'iterable';

    /**
     * @since PHP 7.1
     */
    public const VOID = 'void';

    /**
     * @since PHP 7.2
     */
    public const OBJECT = 'object';

    /**
     * @since PHP 8.0
     */
    public const MIXED = 'mixed';

    /**
     * @since PHP 8.0
     */
    public const STATIC = 'static';

    /**
     * @since PHP 8.0
     */
    public const NULL = 'null';

    /**
     * @since PHP 8.1
     */
    public const NEVER = 'never';

    /**
     * @since PHP 8.1
     */
    public const FALSE = 'false';

    /**
     * @since PHP 8.2
     */
    public const TRUE = 'true';
}
