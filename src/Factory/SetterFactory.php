<?php

namespace Alexanevsky\GetterSetterAccessorBundle\Factory;

use Alexanevsky\GetterSetterAccessorBundle\Builder\SetterBuilder;
use Alexanevsky\GetterSetterAccessorBundle\Model\Setter;
use function Symfony\Component\String\u;

class SetterFactory
{
    private array $settersByClasses = [];

    /**
     * @param class-string $class
     *
     * @return Setter[]
     */
    public function createSettersFromClass(string $class): array
    {
        if (isset($this->settersByClasses[$class])) {
            return $this->settersByClasses[$class];
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
                && 1 === $refl->getNumberOfRequiredParameters()
                && u($refl->getName())->startsWith('set')
        );

        /** @var SetterBuilder[] */
        $settersBuilders = [];

        foreach ($reflProperties as $reflProperty) {
            $settersBuilders[$reflProperty->getName()] = (new SetterBuilder($reflProperty->getName()))
                ->setReflectionProperty($reflProperty);
        }

        foreach ($reflMethods as $reflMethod) {
            $name = u($reflMethod->getName())->trimPrefix('set')->camel()->toString();

            if (!isset($settersBuilders[$name])) {
                $settersBuilders[$name] = new SetterBuilder($name);
            }

            $settersBuilders[$name]->setReflectionMethod($reflMethod);
        }

        $setters = array_map(
            fn (SetterBuilder $setterBuilder) => $setterBuilder->build(),
            $settersBuilders
        );

        $this->settersByClasses[$class] = $setters;

        return $setters;
    }
}
