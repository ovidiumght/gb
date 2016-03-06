<?php
namespace FlatFileDb\Storage;

use FlatFileDb\Document\DocumentInterface;
use FlatFileDb\Hydrator\HydratorInterface;

interface StorageInterface
{
    /**
     * 
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator);
    
    /**
     * 
     * @param DocumentInterface $document
     */
    public function setDocumentPrototype(DocumentInterface $document);
    
    /**
     * 
     * @param string $filename
     * @param DocumentInterface $document
     * @return int
     */
    public function write($filename, DocumentInterface $document);
    
    /**
     * 
     * @param string $filename
     * @param bool $hydrate
     * @return DocumentInterface|array
     */
    public function read($filename, $hydrate = true);
    
    /**
     * 
     * @param string $filename
     * @return bool
     */
    public function delete($filename);
}