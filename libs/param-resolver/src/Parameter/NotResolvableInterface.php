<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Parameter;

interface NotResolvableInterface
{
    /**
     * @return \ReflectionParameter
     */
    public function getParameter(): \ReflectionParameter;
}
