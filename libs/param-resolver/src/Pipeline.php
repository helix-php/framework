<?php

declare(strict_types=1);

namespace Helix\ParamResolver;

use Helix\Contracts\ParamResolver\MiddlewareInterface;
use Helix\Contracts\ParamResolver\MutablePipelineInterface;
use Helix\Contracts\ParamResolver\ResolverInterface;
use Helix\ParamResolver\Middleware\DefaultValueResolver;
use Helix\ParamResolver\Middleware\NullableParameterResolver;
use Helix\ParamResolver\Pipeline\Next;

final class Pipeline implements MutablePipelineInterface
{
    /**
     * @var list<MiddlewareInterface>
     */
    private array $resolvers = [];

    /**
     * @param iterable<MiddlewareInterface> $resolvers
     */
    public function __construct(
        iterable $resolvers = [],
    ) {
        $this->prepend([...$resolvers]);
    }

    /**
     * @return static
     */
    public static function createWithDefaultResolvers(): self
    {
        return new self([
            new DefaultValueResolver(),
            new NullableParameterResolver(),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function process(\ReflectionParameter $parameter, ResolverInterface $resolver): mixed
    {
        foreach (\array_reverse($this->resolvers) as $middleware) {
            $resolver = new Next($middleware, $resolver);
        }

        return $resolver->handle($parameter);
    }

    /**
     * {@inheritDoc}
     */
    public function append(MiddlewareInterface|iterable $resolver, \Closure $after = null): void
    {
        if ($resolver instanceof MiddlewareInterface) {
            $resolver = [$resolver];
        }

        if ($after === null) {
            \array_push($this->resolvers, ...$resolver);

            return;
        }

        $this->appendAfter($resolver, $after);
    }

    /**
     * @param iterable<MiddlewareInterface> $resolvers
     * @param \Closure(MiddlewareInterface):bool $after
     * @return void
     */
    private function appendAfter(iterable $resolvers, \Closure $after): void
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(MiddlewareInterface|iterable $resolver, \Closure $before = null): void
    {
        if ($resolver instanceof MiddlewareInterface) {
            $resolver = [$resolver];
        }

        if ($before === null) {
            \array_unshift($this->resolvers, ...$resolver);

            return;
        }

        $this->prependBefore($resolver, $before);
    }

    /**
     * @param iterable<MiddlewareInterface> $resolvers
     * @param \Closure(MiddlewareInterface):bool $before
     * @return void
     */
    private function prependBefore(iterable $resolvers, \Closure $before): void
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
