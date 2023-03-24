<?php

namespace Alexanevsky\GetterSetterAccessorBundle\Model;

class Setter
{
    public function __construct(
        private string  $name,
        private ?string $property,
        private ?string $method,
        private bool    $nullable,
        private array   $types,
        private array   $attributes
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function isSettableByProperty(): bool
    {
        return !empty($this->property);
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function isSettableByMethod(): bool
    {
        return !empty($this->method);
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param class-string $attributeClass
     */
    public function getAttribute(string $attributeClass): ?object
    {
        return $this->getReflectionAttribute($attributeClass)?->newInstance();
    }

    /**
     * @param class-string $attributeClass
     */
    public function hasAttribute(string $attributeClass): bool
    {
        return null !== $this->getReflectionAttribute($attributeClass);
    }

    private function getReflectionAttribute(string $attributeClass): ?\ReflectionAttribute
    {
        return array_values(array_filter($this->attributes, fn (\ReflectionAttribute $a) => $a->getName() === $attributeClass))[0] ?? null;
    }
}
