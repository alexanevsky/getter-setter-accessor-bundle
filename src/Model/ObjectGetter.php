<?php

namespace Alexanevsky\GetterSetterAccessorBundle\Model;

class ObjectGetter
{
    public function __construct(
        private object $object,
        private Getter $getter
    ) {
    }

    public function getValue(): mixed
    {
        if ($this->getter->isGettableByMethod()) {
            $method = $this->getter->getMethod();

            return $this->object->$method();
        } elseif ($this->getter->isGettableByProperty()) {
            $property = $this->getter->getProperty();

            return $this->object->$property ?? null;
        }

        return null;
    }

    public function getName(): string
    {
        return $this->getter->getName();
    }

    public function isNullable(): bool
    {
        return $this->getter->isNullable();
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->getter->getTypes();
    }

    /**
     * @return object[]
     */
    public function getAttributes(): array
    {
        return $this->getter->getAttributes();
    }

    /**
     * @param class-string $attributeClass
     */
    public function getAttribute(string $attributeClass): ?object
    {
        return $this->getter->getAttribute($attributeClass);
    }

    /**
     * @param class-string $attributeClass
     */
    public function hasAttribute(string $attributeClass): bool
    {
        return $this->getter->hasAttribute($attributeClass);
    }
}
