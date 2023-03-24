<?php

namespace Alexanevsky\GetterSetterAccessorBundle\Model;

class ObjectSetter
{
    public function __construct(
        private object $object,
        private Setter $setter
    ) {
    }

    public function setValue(mixed $value): void
    {
        if ($this->setter->isSettableByMethod()) {
            $method = $this->setter->getMethod();
            $this->object->$method($value);
        } elseif ($this->setter->isSettableByProperty()) {
            $property = $this->setter->getProperty();
            $this->object->$property = $value;
        }
    }

    public function getName(): string
    {
        return $this->setter->getName();
    }

    public function isNullable(): bool
    {
        return $this->setter->isNullable();
    }

    public function getTypes(): array
    {
        return $this->setter->getTypes();
    }

    /**
     * @param class-string $attributeClass
     */
    public function getAttribute(string $attributeClass): ?object
    {
        return $this->setter->getAttribute($attributeClass);
    }

    /**
     * @param class-string $attributeClass
     */
    public function hasAttribute(string $attributeClass): bool
    {
        return $this->setter->hasAttribute($attributeClass);
    }
}
