<?php

declare(strict_types=1);

namespace Helix\ParamInfo;

interface TypeHint
{
    /**
     * @since PHP 5.x
     */
    public const CALLABLE = 'callable';

    /**
     * @since PHP 5.x
     */
    public const ARRAY = 'array';

    /**
     * @since PHP 7.0
     */
    public const INT = 'int';

    /**
     * @since PHP 7.0
     */
    public const FLOAT = 'float';

    /**
     * @since PHP 7.0
     */
    public const STRING = 'string';

    /**
     * @since PHP 7.0
     */
    public const BOOL = 'bool';

    /**
     * @since PHP 7.0
     */
    public const SELF = 'self';

    /**
     * @since PHP 7.0
     */
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
