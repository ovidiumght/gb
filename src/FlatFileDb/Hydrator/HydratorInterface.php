<?php
namespace FlatFileDb\Hydrator;

interface HydratorInterface
{
    public function hydrate($object, $values);
    public function extract($object);
}