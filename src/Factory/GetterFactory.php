<?php

namespace Alexanevsky\GetterSetterAccessorBundle\Factory;

use Alexanevsky\GetterSetterAccessorBundle\Builder\GetterBuilder;
use Alexanevsky\GetterSetterAccessorBundle\Model\Getter;
use function Symfony\Component\String\u;

class GetterFactory
{
    private array $gettersByClasses = [];

    /**
     * @param class-string $class
     *
     * @return Getter[]
     */
    public function createGettersFromClass(string $class): array
    {
        if (isset($this->gettersByClasses[$class])) {
            return $this->gettersByClasses[$class];
        }

        $reflClass = new \ReflectionClass($class);

        $reflProperties = array_filter(
            $reflClass->getProperties(),
            fn (\ReflectionProperty $refl) => $refl->isPublic()
                && !$refl->isStatic()
                && !u($refl->getName())->startsWith('_')
        );

        $reflMethods = array_filter(
            $reflClass->getMethods(),
            fn (\ReflectionMethod $refl) => $refl->isPublic()
                && !$refl->isStatic()
                && !$refl->getNumberOfRequiredParameters()
                && u($refl->getName())->startsWith(['get', 'is'])
        );

        /** @var GetterBuilder[] */
        $gettersBuilders = [];

        foreach ($reflProperties as $reflProperty) {
            $gettersBuilders[$reflProperty->getName()] = (new GetterBuilder($reflProperty->getName()))
                ->setReflectionProperty($reflProperty);
        }

        foreach ($reflMethods as $reflMethod) {
            $name = u($reflMethod->getName())->trimPrefix(['get', 'is'])->camel()->toString();

            if (!isset($gettersBuilders[$name])) {
                $gettersBuilders[$name] = new GetterBuilder($name);
            }

            $gettersBuilders[$name]->setReflectionMethod($reflMethod);
        }

        $getters = array_map(
            fn (GetterBuilder $getterBuilder) => $getterBuilder->build(),
            $gettersBuilders
        );

        $this->gettersByClasses[$class] = $getters;

        return $getters;
    }
}
