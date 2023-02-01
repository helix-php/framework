<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Parameter;

use Helix\ParamInfo\Type;

final class Placeholder implements NotResolvableInterface
{
    /**
     * @var Type
     */
    public readonly Type $info;

    /**
     * @param \ReflectionParameter $parameter
     */
    public function __construct(
        private readonly \ReflectionParameter $parameter,
    ) {
        $this->info = Type::fromParameter($this->parameter);
    }

    /**
     * @return \ReflectionParameter
     */
    public function getParameter(): \ReflectionParameter
    {
        return $this->parameter;
    }
}
