<?php

namespace FlatFileDb\Util;

use FlatFileDb\Exception\Exception;

class Directory
{
    /**
     *
     * @var string
     */
    protected $root;

    /**
     * 
     * @param string $root
     * @throws Exception
     */
    public function __construct($root)
    {
        if (!is_dir($root)) {
            throw new Exception('Directory "' . $root . '" does not exists.');
        }

        if (!is_writable($root)) {
            throw new Exception('Directory "' . $root . '" is not writable by user: ' . get_current_user());
        }

        $this->root = rtrim($root, DIRECTORY_SEPARATOR);
    }
    
    /**
     * 
     * @param string $name
     * @param octal $mode
     * @return boolean
     * @throws Exception
     */
    public function create($name, $mode = 0750)
    {
        $path = $this->getRepositoryPath($name);
        if (!mkdir($path, $mode)) {
            throw new Exception('Failed to create directory: "' . $path);
        }
        return true;
    }

    public function createIfNotExists($name, $mode = 0750)
    {
        if (!$this->exists($name)) {
            return $this->create($name, $mode);
        }
        return true;
    }
    
    public function exists($name)
    {
        return is_dir($this->getRepositoryPath($name));
    }
    
    public function getRepositoryPath($name)
    {
        return $this->root . DIRECTORY_SEPARATOR . $name;
    }
}
