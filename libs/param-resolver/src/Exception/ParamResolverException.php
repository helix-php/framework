<?php

declare(strict_types=1);

namespace Helix\ParamResolver\Exception;

use Helix\Contracts\ParamResolver\Exception\ParamResolverExceptionInterface;

class ParamResolverException extends \Exception implements
    ParamResolverExceptionInterface
{
}
