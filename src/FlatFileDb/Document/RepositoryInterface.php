<?php
namespace FlatFileDb\Document;

use DirectoryIterator;
use FlatFileDb\Hydrator\HydratorInterface;
use FlatFileDb\QueryBuilder;

interface RepositoryInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return DirectoryIterator
     */
    public function getIterator();

    /**
     * @return  HydratorInterface
     */
    public function getHydrator();

    /**
     * @return DocumentInterface
     */
    public function getDocumentPrototype();

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder();

}