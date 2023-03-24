<?php

namespace Alexanevsky\GetterSetterAccessorBundle\Builder;

use Alexanevsky\GetterSetterAccessorBundle\Exception\EmptySetterException;
use Alexanevsky\GetterSetterAccessorBundle\Model\Setter;

class SetterBuilder
{
    private string $name;

    private ?\ReflectionProperty $reflProperty = null;

    private ?\ReflectionMethod $reflMethod = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function build(): Setter
    {
        if (!$this->reflProperty && !$this->reflMethod) {
            throw new EmptySetterException('Cannot create setter without property and method');
        }

        /** @var \ReflectionUnionType|\ReflectionNamedType|null */
        $reflType = $this->reflMethod
            ? $this->reflMethod->getParameters()[0]->getType()
            : $this->reflProperty->getType();

        $isNullable = $reflType?->allowsNull() ?? true;

        if ($reflType instanceof \ReflectionNamedType) {
            $types = [$reflType->getName()];
        } elseif ($reflType instanceof \ReflectionUnionType) {
            $types = array_values(
                array_filter(
                    array_map(fn (\ReflectionNamedType $type) => $type->getName(), $reflType->getTypes()),
                    fn (string $type) => 'null' !== $type
                )
            );
        } else {
            $types = [];
        }

        $attributes = array_merge(
            $this->reflMethod?->getAttributes() ?? [],
            $this->reflProperty?->getAttributes() ?? []
        );

        return new Setter(
            $this->name,
            $this->reflProperty?->getName(),
            $this->reflMethod?->getName(),
            $isNullable,
            $types,
            $attributes,
        );
    }

    public function setReflectionProperty(\ReflectionProperty $reflProperty): static
    {
        $this->reflProperty = $reflProperty;

        return $this;
    }

    public function setReflectionMethod(\ReflectionMethod $reflMethod): static
    {
        $this->reflMethod = $reflMethod;

        return $this;
    }
}
