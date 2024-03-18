<?php

namespace App\Bakery\config;

use App\Service\ClassBrowser;
use Doctrine\Common\Collections\Collection;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use Override;
use ReflectionException;
use ReflectionParameter;
use ReflectionProperty;

abstract class AbstractBakery implements BakeryInterface
{
    /**
     * An array that only contains one or many objects.
     */
    protected array $objects;
    protected Generator $faker;
    private array $requirements = [
        'makeOne' => false,
        'makeMany' => false,
    ];

    /**
     * Make Faker available in all factories.
     */
    public function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     * Create one object.
     *
     * @return $this
     *
     * @throws ReflectionException
     */
    public function makeOne(): self
    {
        $this->objects = [$this->mountObject()];
        $this->requirements['makeOne'] = true;

        return $this;
    }

    /**
     * Create $number number of objects.
     *
     * @return $this
     *
     * @throws ReflectionException
     */
    public function makeMany(int $number): self
    {
        $many = [];
        for ($i = 0; $i < $number; $i++) {
            $many[] = $this->mountObject();
        }
        $this->objects = $many;
        $this->requirements['makeMany'] = true;

        return $this;
    }

    /**
     * Creates the object based on the given $this->build() generator
     * If a property is omitted in the generator, it tries to set it null.
     *
     * @throws Exception
     * @throws ReflectionException
     */
    private function mountObject(): object
    {
        $objectClass = $this->getBakeryModelClass();
        $object = $this->instantiateFakeObject()['object'];
        $objectProperties = $this->instantiateFakeObject()['properties'];

        $objectPropertyNames = array_map(fn(ReflectionProperty $param) => $param->name, $objectProperties);

        $build = $this->build()->current();
        $buildKeys = array_keys($build);

        $nulledProperties = [];
        foreach (array_diff($objectPropertyNames, $buildKeys) as $unBuiltProperty) {
            $this->setProperty($objectClass, $unBuiltProperty, $object, null);
            $nulledProperties[] = $unBuiltProperty;
        }
        $objectPropertyNames = array_values(array_diff($objectPropertyNames, $nulledProperties));

        for ($i = 0; $i <= count($objectPropertyNames) - 1; $i++) {

            if (in_array($buildKeys[$i], $objectPropertyNames)) {
                $this->setProperty($objectClass, $objectPropertyNames[$i], $object, $build[$objectPropertyNames[$i]]);
            }
        }

        return $object;
    }

    /**
     * @throws Exception
     */
    private function instantiateFakeObject(): array
    {
        $objectClass = $this->getBakeryModelClass();
        $object = ClassBrowser::instantiateWithoutConstructor($objectClass);
        $properties = ClassBrowser::findAllProperties($objectClass);

        ClassBrowser::isRelational($objectClass, $properties[rand(0, count($properties) - 1)]);

        for ($i = 0; $i < count($properties); $i++) {
            dump(ClassBrowser::isRelational($objectClass, $properties[$i]));
            if ($properties[$i]->getName() === 'id' ||
                $properties[$i]->getType()->getName() === Collection::class ||
                ClassBrowser::isRelational($objectClass, $properties[$i])
            ) {
                unset($properties[$i]);
                $properties = array_values($properties);
            }
        }

        return
            [
                'object' => $object,
                'properties' => $properties
            ];
    }

    /**
     * Gets the currently called Bakery model class.
     *
     * @throws Exception
     */
    private function getBakeryModelClass(): string
    {
        return ClassBrowser::findAttribute(get_class($this), AsBakery::class)->getArguments()['bakes'];
    }

    /**
     * [Optional] Distributes all $criteria to all objects in $this->objects.
     *
     * @return $this
     *
     * @throws ReflectionException
     * @throws Exception
     *
     * @example ->withCriteria(['firstname' => 'Maxime', 'lastname' => 'de la Rocheterie'])->
     */
    public function withCriteria(array $criteria): self
    {
        $this->checkRequirements(['makeMany', 'makeOne']);

        $this->applyToObjectsInArray(
        /**
         * @throws ReflectionException
         */
            fn(object $object) => $this->applyCriteria($criteria, $object)
        );

        return $this;
    }

    /**
     * Sets $object->$property with the given $value.
     * If the property is public, set it directly.
     * If the property is private, seek setter and set the property through it.
     * If none of the above nor if the property isn't found, throw an Exception.
     *
     * @throws ReflectionException
     * @throws Exception
     */
    private function setProperty(string $classFQCN, string $property, object $object, mixed $value): void
    {
        if (!ClassBrowser::propertyExists($classFQCN, $property)) {
            throw new Exception('Cannot find ' . $property . ' in class "' . $classFQCN . '"' . PHP_EOL);
        }

        if (ClassBrowser::isPropertyPublic($classFQCN, $property)) {
            $object->$property = $value;
        } elseif (ClassBrowser::hasPropertySetter($classFQCN, $property)) {
            $setter = ClassBrowser::findSetter($classFQCN, $property)?->name;
            $object->$setter($value);
        } else {
            $getter = ClassBrowser::findGetter($classFQCN, $property)?->name;
            if ($getter === null || $object->$getter($property) === null || $object->$getter($property) === '') {
                throw new Exception('Cannot set this property in "' . $classFQCN . '". "' . $property . '" is neither public nor has a Setter.');
            }
        }
    }

    /**
     * Finds the property input in $criteria and sets it to the value in $criteria.
     *
     * @throws ReflectionException
     * @throws Exception
     *
     * @example For an addressObject: $criteria = ['city' => 'Lyon'] means $addressObject->city = 'Lyon';
     */
    private function applyCriteria(array $criteria, object $object): void
    {
        $classFQCN = get_class($object);
        $classProperties = array_map(fn(ReflectionProperty $property) => $property->name, ClassBrowser::findAllProperties($classFQCN));

        $this->processCriteria(
            $criteria,
            $classProperties,
            $classFQCN,
            fn($property, $value) => $this->setProperty($classFQCN, $property, $object, $value)
        );
    }

    /**
     * Process the given criteria array to apply $fn.
     *
     * @throws ReflectionException
     * @throws Exception
     */
    private function processCriteria(array $criteria, array $classProperties, string $classFQCN, callable $fn): void
    {
        $classPropertiesSet = [];
        $classPropertiesToSet = [];
        $choices = implode(', ', $classProperties);

        foreach ($criteria as $field => $value) {
            foreach ($classProperties as $property) {
                $classPropertiesToSet[] = $field;
                if ($property === $field) {
                    $fn($property, $value);
                    $classPropertiesSet[] = $property;
                }
            }
        }

        foreach ($classPropertiesToSet as $propertyToSet) {
            if (!in_array($propertyToSet, $classPropertiesSet)) {
                throw new Exception('Cannot find any property called "' . $propertyToSet . '" in "' . $classFQCN . '".' . PHP_EOL . 'Choices are: ' . $choices);
            }
        }
    }

    /**
     * Last function to call to get the built-up array of objects.
     * If only one object was made, it returns the object, otherwise it returns an array of object.
     *
     * @throws Exception
     */
    public function bake(): array|object
    {
        $this->checkRequirements(['makeMany', 'makeOne']);

        $thisType = $this->getBakeryModelClass();

        foreach ($this->objects as $object) {
            assert($object instanceof $thisType);
        }

        return count($this->objects) === 1 ? $this->objects[0] : $this->objects;
    }

    /**
     * Applies $fn to all objects in $this->objects.
     */
    private function applyToObjectsInArray(callable $fn): void
    {
        foreach ($this->objects as $object) {
            $fn($object);
        }
    }

    /**
     * Just bake a quicky.
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function gimmeOne(): object
    {
        return $this->makeOne()->bake();
    }

    /**
     * Get one of the many made.
     *
     * @context makeMany() has to be called before this.
     *
     * @throws Exception
     */
    public function randomOne(): object
    {
        $this->checkRequirements(['makeMany']);

        return $this->faker->randomElement($this->objects);
    }

    /**
     * Check requirements before calling a method.
     * If $strict is true, all given requirements will have to be true, otherwise, only one true will suffice.
     *
     * @throws Exception
     */
    private function checkRequirements(array $requirements, bool $strict = false): void
    {
        $checked = [];
        foreach ($requirements as $requirement) {
            if ($this->requirements[$requirement]) {
                $checked[] = $this->requirements[$requirement];
            }
        }

        if ($strict) {
            if (in_array(false, $checked)) {
                throw new Exception('All of these methods: ' . implode('", "', $requirements) . ' need to be called before ' . debug_backtrace()[1]['function'] . '".' . PHP_EOL);
            }
        }

        if (!in_array(true, $checked)) {
            throw new Exception('One of these methods: "' . implode('", "', $requirements) . '", needs to be called before "' . debug_backtrace()[1]['function'] . '".' . PHP_EOL);
        }
    }

    #[Override]
    public function build(): \Generator
    {
        yield [];
    }
}
