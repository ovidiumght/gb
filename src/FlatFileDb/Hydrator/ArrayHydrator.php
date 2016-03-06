<?php
namespace FlatFileDb\Hydrator;

use Exception;

class ArrayHydrator implements HydratorInterface
{
    public function extract($object)
    {
        if (!method_exists($object, 'toArray')) {
            throw new Exception('Object ' . (get_class($object)) . ' must implement "toArray" method.');
        }
        return $object->toArray();
    }

    public function hydrate($object, $values)
    {
        if (!method_exists($object, 'fromArray')) {
            throw new Exception('Object ' . (get_class($object)) . ' must implement "fromArray" method.');
        }
        $object->fromArray($values);
    }

}