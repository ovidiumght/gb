<?php
namespace FlatFileDb;

use FlatFileDb\Document\RepositoryInterface;
use FlatFileDb\Exception\Exception;

class Db
{
    /**
     *
     * @var Configuration
     */
    protected $configuration;
    
    /**
     *
     * @var RepositoryInterface[]
     */
    protected $repositories = array();
    
    /**
     * 
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }
    
    /**
     * 
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
    
    /**
     * 
     * @param RepositoryInterface $repository
     */
    public function addRepository(RepositoryInterface $repository)
    {
        if (!$this->hasRepository($repository)) {
            $this->repositories[$repository->getName()] = $repository;
        }
    }

    public function hasRepository($repository)
    {
        if ($repository instanceof RepositoryInterface) {
            $repository = $repository->getName();
        }
        return isset($this->repositories[$repository]);
    }
    
    public function getRepositories()
    {
        return $this->repositories;
    }
    
    public function getRepository($name)
    {
        if ($this->hasRepository($name)) {
            return $this->repositories[$name];
        }
        
        throw new Exception('Unknown repository: ' . $name);
    }
}