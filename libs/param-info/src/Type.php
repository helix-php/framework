<?php

declare(strict_types=1);

namespace Helix\ParamInfo;

final class Type
{
    /**
     * @var \ReflectionType|null
     */
    private static ?\ReflectionType $mixed = null;

    /**
     * @var \ReflectionType
     */
    private readonly \ReflectionType $type;

    /**
     * @param \ReflectionType|null $type
     */
    private function __construct(
        ?\ReflectionType $type,
    ) {
        $this->type = $type ?? self::mixed();
    }

    /**
     * @return \ReflectionType
     * @psalm-suppress NullableReturnStatement
     */
    private static function mixed(): mixed
    {
        return self::$mixed ??= (new \ReflectionMethod(__CLASS__, __FUNCTION__))
            ->getReturnType();
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return self
     */
    public static function fromParameter(\ReflectionParameter $parameter): self
    {
        return new self($parameter->getType());
    }

    /**
     * @return \ReflectionType
     */
    public function getType(): \ReflectionType
    {
        return $this->type;
    }

    /**
     * @param \ReflectionFunctionAbstract $fun
     * @return self
     */
    public static function fromReturnStatement(\ReflectionFunctionAbstract $fun): self
    {
        return new self($fun->getReturnType());
    }

    /**
     * @return bool
     */
    public function allowsBool(): bool
    {
        return $this->match($this->matchBool(...));
    }

    /**
     * @return bool
     */
    public function isBool(): bool
    {
        return $this->type instanceof \ReflectionNamedType
            && $this->matchBool($this->type);
    }

    /**
     * @param \ReflectionNamedType $type
     * @return bool
     */
    private function matchBool(\ReflectionNamedType $type): bool
    {
        return $type->isBuiltin()
            && \in_array($type->getName(), [
                TypeHint::TRUE,
                TypeHint::FALSE,
                TypeHint::BOOL,
            ], true);
    }

    /**
     * @return bool
     */
    public function isIterable(): bool
    {
        return $this->type instanceof \ReflectionNamedType
            && $this->matchIterable($this->type);
    }

    /**
     * @param \ReflectionNamedType $type
     * @return bool
     */
    private function matchIterable(\ReflectionNamedType $type): bool
    {
        return $type->isBuiltin()
            && $type->getName() === TypeHint::ITERABLE;
    }

    /**
     * @return bool
     */
    public function isBuiltin(): bool
    {
        return $this->type instanceof \ReflectionNamedType
            && $this->matchBuiltin($this->type);
    }

    /**
     * @param \ReflectionNamedType $type
     * @return bool
     */
    private function matchBuiltin(\ReflectionNamedType $type): bool
    {
        return $type->isBuiltin();
    }

    /**
     * @return bool
     */
    public function allowsIterable(): bool
    {
        return $this->match($this->matchIterable(...));
    }

    /**
     * @param callable(\ReflectionNamedType):bool $match
     * @return bool
     */
    public function match(callable $match): bool
    {
        return $this->matchType($this->type, $match);
    }

    /**
     * @param \ReflectionType $type
     * @param callable(\ReflectionNamedType):bool $match
     * @return bool
     */
    private function matchType(\ReflectionType $type, callable $match): bool
    {
        return match (true) {
            $type instanceof \ReflectionNamedType => (bool)$match($type),
            $type instanceof \ReflectionUnionType => $this->matchUnionType($type, $match),
            $type instanceof \ReflectionIntersectionType => $this->matchIntersectionType($type, $match),
            default => false,
        };
    }

    /**
     * @param \ReflectionUnionType $type
     * @param callable(\ReflectionNamedType):bool $match
     * @return bool
     */
    private function matchUnionType(\ReflectionUnionType $type, callable $match): bool
    {
        foreach ($type->getTypes() as $child) {
            if ($this->matchType($child, $match)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ReflectionIntersectionType $type
     * @param callable(\ReflectionNamedType):bool $match
     * @return bool
     */
    private function matchIntersectionType(\ReflectionIntersectionType $type, callable $match): bool
    {
        foreach ($type->getTypes() as $child) {
            if (!$this->matchType($child, $match)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function allowsBuiltin(): bool
    {
        return $this->match($this->matchBuiltin(...));
    }

    /**
     * @return bool
     */
    public function isScalar(): bool
    {
        return $this->type instanceof \ReflectionNamedType
            && $this->matchScalar($this->type);
    }

    /**
     * @param \ReflectionNamedType $type
     * @return bool
     */
    private function matchScalar(\ReflectionNamedType $type): bool
    {
        return \in_array($type->getName(), [
            TypeHint::INT,
            TypeHint::FLOAT,
            TypeHint::STRING,
            TypeHint::BOOL,
            TypeHint::FALSE,
            TypeHint::TRUE,
        ], true);
    }

    /**
     * @return bool
     */
    public function allowsScalar(): bool
    {
        return $this->match($this->matchScalar(...));
    }

    /**
     * @param object $instance
     * @return bool
     */
    public function isInstanceOf(object $instance): bool
    {
        return $this->type instanceof \ReflectionNamedType
            && $this->matchInstanceOf($this->type, $instance);
    }

    /**
     * @param \ReflectionNamedType $type
     * @param object $expected
     * @return bool
     */
    private function matchInstanceOf(\ReflectionNamedType $type, object $expected): bool
    {
        return !$type->isBuiltin() && $expected instanceof ($type->getName());
    }

    /**
     * @param object $instance
     * @return bool
     */
    public function allowsInstanceOf(object $instance): bool
    {
        return $this->match(fn (\ReflectionNamedType $type): bool =>
            $this->matchInstanceOf($type, $instance)
        );
    }

    /**
     * @param class-string $class
     * @return bool
     */
    public function isSubclassOf(string $class): bool
    {
        return $this->type instanceof \ReflectionNamedType
            && $this->matchSubclassOf($this->type, $class);
    }

    /**
     * @param \ReflectionNamedType $type
     * @param class-string $expected
     * @return bool
     */
    private function matchSubclassOf(\ReflectionNamedType $type, string $expected): bool
    {
        return !$type->isBuiltin() && \is_a($expected, $type->getName(), true);
    }

    /**
     * @param class-string $class
     * @return bool
     */
    public function allowsSubclassOf(string $class): bool
    {
        return $this->match(fn (\ReflectionNamedType $type): bool =>
            $this->matchSubclassOf($type, $class)
        );
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        if ($this->isMixed()) {
            return true;
        }

        return $this->type->allowsNull();
    }

    /**
     * @return bool
     */
    public function isMixed(): bool
    {
        return $this->type instanceof \ReflectionNamedType
            && $this->type->getName() === TypeHint::MIXED;
    }
}
