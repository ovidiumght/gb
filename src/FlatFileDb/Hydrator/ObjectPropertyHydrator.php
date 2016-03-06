<?php
namespace FlatFileDb\Hydrator;

class ObjectPropertyHydrator implements HydratorInterface
{
    public function extract($object)
    {
        return get_object_vars($object);
    }

    public function hydrate($object, $values)
    {
        foreach ($values as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $value;
            }
        }
        return $object;
    }

}