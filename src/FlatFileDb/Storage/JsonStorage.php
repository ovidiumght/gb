<?php
namespace FlatFileDb\Storage;

use FlatFileDb\Document\DocumentInterface;
use FlatFileDb\Exception\Exception;
use FlatFileDb\Hydrator\HydratorInterface;

class JsonStorage extends AbstractStorage
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

    /**
     * 
     * @param string $filename
     * @param DocumentInterface $document
     * @return int
     */
    public function write($filename, DocumentInterface $document)
    {
        $handle = fopen($filename, 'w');
        $result = fwrite($handle, json_encode($this->hydrator->extract($document), JSON_PRETTY_PRINT));
        fclose($handle);
        return $result;
    }
    
    /**
     * 
     * @param string $filename
     * @return DocumentInterface|array
     * @throws Exception
     */
    public function read($filename, $hydrate = true)
    {
        if (!file_exists($filename)) {
            return null;
        }
        $json = file_get_contents($filename);
        $array = json_decode($json, true);
        if (!$array) {
            throw new Exception(json_last_error_msg());
        }
        
        if ($hydrate) {
            return $this->hydrator->hydrate(new $this->documentPrototype, $array);
        } else {
            return $array;
        }
    }
    
    /**
     * 
     * @param string $filename
     * @throws Exception
     * @return bool
     */
    public function delete($filename)
    {
        if (file_exists($filename)) {
            if (!is_writable($filename)) {
                throw new Exception('File ' . $filename . ' is not writable. Unable to remove.');
            }
            return unlink($filename);
        }
        return false;
    }
}