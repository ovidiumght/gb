<?php
namespace FlatFileDb\Storage;

use FlatFileDb\Document\DocumentInterface;
use FlatFileDb\Hydrator\HydratorInterface;

abstract class AbstractStorage implements StorageInterface
{
    /**
     *
     * @var HydratorInterface
     */
    protected $hydrator;
    
    /**
     *
     * @var DocumentInterface
     */
    protected $documentPrototype;
    
    /**
     * 
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }
    
    /**
     * 
     * @param DocumentInterface $documentPrototype
     */
    public function setDocumentPrototype(DocumentInterface $documentPrototype)
    {
        $this->documentPrototype = $documentPrototype;
    }
}