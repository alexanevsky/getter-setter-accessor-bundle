<?php

namespace Alexanevsky\GetterSetterAccessorBundle;

use Alexanevsky\GetterSetterAccessorBundle\Factory\GetterFactory;
use Alexanevsky\GetterSetterAccessorBundle\Factory\ObjectAccessorFactory;
use Alexanevsky\GetterSetterAccessorBundle\Factory\SetterFactory;
use Alexanevsky\GetterSetterAccessorBundle\Model\Getter;
use Alexanevsky\GetterSetterAccessorBundle\Model\ObjectAccessor;
use Alexanevsky\GetterSetterAccessorBundle\Model\Setter;

class GetterSetterAccessor
{
    public function __construct(
        private ObjectAccessorFactory   $objectsAccessorFactory,
        private GetterFactory           $gettersFactory,
        private SetterFactory           $settersFactory
    ) {
    }

    public function createAccessor(object $object): ObjectAccessor
    {
        return $this->objectsAccessorFactory->createObjectAccessor(
            $object,
            $this->getGetters($object::class),
            $this->getSetters($object::class)
        );
    }

    /**
     * @param class-string|object $class
     *
     * @return Getter[]
     */
    public function getGetters(string|object $class): array
    {
        if (is_object($class)) {
            $class = $class::class;
        }

        return $this->gettersFactory->createGettersFromClass($class);
    }

    /**
     * @param class-string|object $class
     *
     * @return Setter[]
     */
    public function getSetters(string|object $class): array
    {
        if (is_object($class)) {
            $class = $class::class;
        }

        return $this->settersFactory->createSettersFromClass($class);
    }
}
