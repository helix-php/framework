<?php

declare(strict_types=1);

namespace Helix\ParamResolver;

use Helix\Contracts\ParamResolver\FactoryInterface;
use Helix\Contracts\ParamResolver\MiddlewareInterface;
use Helix\Contracts\ParamResolver\MutablePipelineInterface;
use Helix\Contracts\ParamResolver\PipelineInterface;
use Helix\Contracts\ParamResolver\ResolverInterface;
use Helix\ParamResolver\Factory\ReaderInterface;
use Helix\ParamResolver\Factory\StatelessReader;

final class Factory implements FactoryInterface
{
    /**
     * @param ResolverInterface $resolver
     * @param MutablePipelineInterface $pipeline
     * @param ReaderInterface $reader
     */
    public function __construct(
        private readonly ResolverInterface $resolver = new ExceptionResolver(),
        private readonly MutablePipelineInterface $pipeline = new Pipeline(),
        private readonly ReaderInterface $reader = new StatelessReader(),
    ) {}

    /**
     * {@inheritDoc}
     */
    public function fromCallable(callable $callable, iterable $resolvers = []): iterable
    {
        $context = $this->pipeline($resolvers);

        foreach ($this->reader->fromCallable($callable) as $parameter) {
            yield $context->process($parameter, $this->resolver);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fromMethod(object|string $class, string $method, iterable $resolvers = []): iterable
    {
        $context = $this->pipeline($resolvers);

        foreach ($this->reader->fromMethod($class, $method) as $parameter) {
            yield $context->process($parameter, $this->resolver);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fromFunction(string $function, iterable $resolvers = []): iterable
    {
        $context = $this->pipeline($resolvers);

        foreach ($this->reader->fromFunction($function) as $parameter) {
            yield $context->process($parameter, $this->resolver);
        }
    }


    /**
     * @param iterable<MiddlewareInterface> $resolvers
     * @return PipelineInterface
     */
    private function pipeline(iterable $resolvers): PipelineInterface
    {
        if ($resolvers === []) {
            return $this->pipeline;
        }

        $context = clone $this->pipeline;
        $context->prepend($resolvers);

        return $context;
    }
}
