<?php

namespace App\Service;

use App\Entity\Lodging;
use App\Entity\Message;
use App\Entity\Reservation;
use App\Entity\ReservationStatus;
use App\Entity\Review;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use ReflectionException;
use ReflectionMethod;

class VueDataFormatter
{
    /**
     * @throws ReflectionException
     */
    public static function makeVueObject(object $object, array $properties = []): array
    {
        $r = [];
        $objectFqcn = get_class($object);
        $allProperties = ClassBrowser::findAllProperties($objectFqcn);

        foreach ($allProperties as $property) {
            if (in_array($property->getName(), $properties)) {
                $getter = ClassBrowser::findGetter($objectFqcn, $property->getName());
                assert($getter instanceof ReflectionMethod);
                $getterName = $getter->getName();

                $value = $object->$getterName();

                match (true) {
                    $value instanceof DateTimeInterface => $value = $value->format('d-m-Y'),
                    $value instanceof User => $value = $value->getFirstname() . ' ' . $value->getLastname(),
                    $value instanceof ReservationStatus => $value = $value->getName(),
                    $value instanceof Collection => $value = array_map(function ($object) {
                        match (true) {
                            $object instanceof Message => $collectionProperty = 'subject',
                            $object instanceof Review => $collectionProperty = 'rate',
                            default => $collectionProperty = 'name'
                        };
                        return self::makeVueObject($object, [$collectionProperty])[$collectionProperty];
                    }, $value->toArray()),
                    default => $value
                };
                $r[$property->getName()] = $value;
            }
        }
        return $r;
    }
}