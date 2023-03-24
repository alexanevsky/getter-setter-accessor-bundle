<?php

namespace Alexanevsky\GetterSetterAccessorBundle\Model;

use Alexanevsky\GetterSetterAccessorBundle\Exception\MissedGetterException;
use Alexanevsky\GetterSetterAccessorBundle\Exception\MissedSetterException;
use function Symfony\Component\String\u;

class ObjectAccessor
{
    /**
     * @param ObjectGetter[] $getters
     * @param ObjectSetter[] $setters
     */
    public function __construct(
        private object $object,
        private array $getters,
        private array $setters
    ) {
    }

    public function getObject(): object
    {
        return $this->object;
    }

    /**
     * @return ObjectGetter[]
     */
    public function getGetters(): array
    {
        return array_values($this->getters);
    }

    public function getGetter(string $propertyName): ?ObjectGetter
    {
        $propertyName = u($propertyName)->camel()->toString();

        return array_values(array_filter($this->getters, fn (ObjectGetter $getter) => $getter->getName() === $propertyName))[0] ?? null;
    }

    public function hasGetter(string $propertyName): bool
    {
        return null !== $this->getGetter($propertyName);
    }

    public function getValue(string $propertyName): mixed
    {
        if (!$this->hasGetter($propertyName)) {
            throw new MissedGetterException(sprintf('%s has not getter of "%s"', $this->object::class, $propertyName));
        }

        return $this->getGetter($propertyName)->getValue();
    }

    /**
     * @return ObjectSetter[]
     */
    public function getSetters(): array
    {
        return array_values($this->setters);
    }

    public function getSetter(string $propertyName): ?ObjectSetter
    {
        $propertyName = u($propertyName)->camel()->toString();

        return array_values(array_filter($this->setters, fn (ObjectSetter $setter) => $setter->getName() === $propertyName))[0] ?? null;
    }

    public function hasSetter(string $propertyName): bool
    {
        return null !== $this->getSetter($propertyName);
    }

    public function setValue(string $propertyName, mixed $value): void
    {
        if (!$this->hasSetter($propertyName)) {
            throw new MissedSetterException(sprintf('%s has not setter of "%s"', $this->object::class, $propertyName));
        }

        $this->getSetter($propertyName)->setValue($value);
    }
}
