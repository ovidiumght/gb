<?php
namespace FlatFileDb\Document;

use FlatFileDb\Hydrator\HydratorInterface;
use FlatFileDb\Hydrator\ObjectPropertyHydrator;
use FlatFileDb\Storage\JsonStorage;
use FlatFileDb\Storage\StorageInterface;

class RepositoryConfig
{
    const DEFAULT_HYDRATOR_CLASS = ObjectPropertyHydrator::class;
    const DEFAULT_STORAGE_CLASS = JsonStorage::class;
    const DEFAULT_DOCUMENT_CLASS = Document::class;
    
    /**
     *
     * @var DocumentInterface
     */
    protected $documentPrototype;
    
    /**
     *
     * @var StorageInterface
     */
    protected $storage;
    
    /**
     *
     * @var HydratorInterface
     */
    protected $hydrator;
    
    /**
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }
    
    /**
     * 
     * @param array $options
     */
    public function setOptions(array $options)
    {
        // document prototype
        $documentClass = self::DEFAULT_DOCUMENT_CLASS;
        if (isset($options['document_prototype'])) {
            $documentClass = $options['document_prototype'];
        }
        $this->documentPrototype = new $documentClass;
        
        // hydrator
        $hydratorClass = self::DEFAULT_HYDRATOR_CLASS;
        if (isset($options['hydrator'])) {
            $hydratorClass = $options['hydrator'];
        }
        $this->hydrator = new $hydratorClass;
        
        // storage backend
        $storageClass = self::DEFAULT_STORAGE_CLASS;
        if (isset($options['storage'])) {
            $storageClass = $options['storage'];
        }
        $this->storage = new $storageClass;
    }
    
    /**
     * 
     * @return DocumentInterface
     */
    public function getDocumentPrototype()
    {
        return $this->documentPrototype;
    }

    /**
     * 
     * @return StorageInterface
     */
    public function getStorage()
    {
        $this->storage->setHydrator($this->getHydrator());
        $this->storage->setDocumentPrototype($this->getDocumentPrototype());
        return $this->storage;
    }

    /**
     * 
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * 
     * @param DocumentInterface $documentPrototype
     */
    public function setDocumentPrototype(DocumentInterface $documentPrototype)
    {
        $this->documentPrototype = $documentPrototype;
    }

    /**
     * 
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * 
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

}