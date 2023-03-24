# Getter & Setter Accessor

This library provides functions to read and write from/to an object and gets the list of object getters and setters.

## Table of Contents
1. [First Step](#first-step)
2. [Get Value](#get-value)
3. [Set Value](#set-value)
4. [Get Getters List](#get-getters-list)
5. [Get Getters List](#get-getters-list)

## First Step

Add `GetterSetterAccessor` to the constructor of controller or service:

```php
use Alexanevsky\GetterSetterAccessorBundle\GetterSetterAccessor;

public function __construct(
    private GetterSetterAccessor $getterSetterAccessor
) {}
```

Then let's get an accessor of some object (it can be, for example, some model):

```php
$objectAccessor = $this->getterSetterAccessor->createAccessor($objectAccessor);
```

## Get Value

We can get the value of any property using the `getValue()` method, for example:

```php
echo $objectAccessor->getValue('userName');
echo $objectAccessor->getValue('user_name');
```

The property name can be passed in both camel and snake cases. It will be converted to the one that is actually used.

If the object does not have a getter, an exception will be thrown. Before using `getValue()` we can make sure the getter exists using the `hasGetter()` method:

```php
if ($objectAccessor->hasGetter('userName')) {
    echo $objectAccessor->getValue('userName');
}
```

## Set Value

We can set the value of any property using the `setValue()` method, for example:

```php
echo $objectAccessor->SetValue('userName', 'John Doe');
echo $objectAccessor->SetValue('user_name', 'John Doe');
```

The property name can be passed in both camel and snake cases. It will be converted to the one that is actually used.

If the object does not have a setter, an exception will be thrown. Before using `setValue()` we can make sure the setter exists using the `hasSetter()` method:

```php
if ($objectAccessor->hasSetter('userName')) {
    echo $objectAccessor->setValue('userName', 'John Doe');
}
```

## Get Getters List

We can get a list of all available object getters using the `getGetters()` method. As a result, we will get an array of `ObjectGetter[]`, each of which has the following methods:

* `getValue()` - returns a value
* `getName()` - returns the property name
* `isNullable()` - checks if the value allows null
* `getTypes()` - returns a list of types that a value can be
* `getAttribute(attributeClass)` - returns an attribute instance by the its class name
* `getAttribute(attributeClass)` - checks if an attribute exists by its class name

## Get Getters List

We can get a list of all available object getters using the `getGetters()` method. As a result, we will get an array of `ObjectGetter[]`, each of which has the following methods:

* `getValue()` - returns a value
* `getName()` - returns the property name
* `isNullable()` - checks if the value allows null
* `getTypes()` - returns a list of types that a value can be
* `getAttribute(attributeClass)` - returns an attribute instance by the its class name
* `getAttribute(attributeClass)` - checks if an attribute exists by its class name

Good luck!
