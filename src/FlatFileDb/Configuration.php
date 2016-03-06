<?php
namespace FlatFileDb;

use FlatFileDb\Exception\ConfigurationException;
use FlatFileDb\Util\Directory;

class Configuration
{
    /**
     * @var Directory
     */
    protected $directory;
    
    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->setOptions($options);
    }
    
    /**
     * @param array $options
     * @throws ConfigurationException
     */
    public function setOptions(array $options = array())
    {
        if (isset($options['directory'])) {
            $this->directory = new Directory($options['directory']);
        } else {
            throw new ConfigurationException('"directory" option is mandatory.');
        }
    }
    
    /**
     * 
     * @return Directory
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}