<?php

declare(strict_types=1);

namespace Helix\Contracts\Router;

use Helix\Contracts\Http\Method\MethodInterface;

interface RouteInterface
{
    /**
     * @return non-empty-string
     */
    public function getPath(): string;

    /**
     * @return mixed
     */
    public function getHandler(): mixed;

    /**
     * @return MethodInterface
     */
    public function getMethod(): MethodInterface;

    /**
     * @return array<non-empty-string, string>
     */
    public function getParameters(): array;

    /**
     * @return non-empty-string|null
     */
    public function getName(): ?string;
}
