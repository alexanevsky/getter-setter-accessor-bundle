<?php

namespace Alexanevsky\GetterSetterAccessorBundle\Model;

class Getter
{
    /**
     * @param string[]               $types
     * @param \ReflectionAttribute[] $attributes
     */
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

    public function isGettableByProperty(): bool
    {
        return !empty($this->property);
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function isGettableByMethod(): bool
    {
        return !empty($this->method);
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @return object[]
     */
    public function getAttributes(): array
    {
        $output = [];

        foreach ($this->attributes as $attr) {
            if (!isset($output[$attr->getName()])) {
                $output[$attr->getName()] = $this->getAttribute($attr->getName());
            }
        }

        return array_values($output);
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
