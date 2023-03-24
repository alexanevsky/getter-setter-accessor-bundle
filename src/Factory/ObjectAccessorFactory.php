<?php

namespace Alexanevsky\GetterSetterAccessorBundle\Factory;

use Alexanevsky\GetterSetterAccessorBundle\Model\Getter;
use Alexanevsky\GetterSetterAccessorBundle\Model\ObjectAccessor;
use Alexanevsky\GetterSetterAccessorBundle\Model\ObjectGetter;
use Alexanevsky\GetterSetterAccessorBundle\Model\ObjectSetter;
use Alexanevsky\GetterSetterAccessorBundle\Model\Setter;

class ObjectAccessorFactory
{
    /**
     * @param Getter[] $getters
     * @param Setter[] $setters
     */
    public function createObjectAccessor(object $object, array $getters, array $setters): ObjectAccessor
    {
        return new ObjectAccessor(
            $object,
            array_map(fn (Getter $getter) => new ObjectGetter($object, $getter), $getters),
            array_map(fn (Setter $setter) => new ObjectSetter($object, $setter), $setters)
        );
    }
}
