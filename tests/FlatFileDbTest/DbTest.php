<?php
namespace FlatFileDbTest;

use FlatFileDb\Configuration;
use FlatFileDb\Db;
use FlatFileDb\Document\Repository;
use PHPUnit_Framework_TestCase;

class DbTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Db
     */
    protected $db;
    
    protected function setUp()
    {
        $this->db = new Db(new Configuration(array(
            'directory' => 'database'
        )));
    }
    
    public function testAddRepository()
    {
        $this->db->addRepository(new Repository('static', $this->db));
        $this->assertTrue($this->db->hasRepository('static'));
    }
    
    public function testGetRepository()
    {
        $this->db->addRepository(new Repository('static', $this->db));
        $this->assertEquals('static', $this->db->getRepository('static')->getName());
    }
    
    public function testGetRepositories()
    {
        $this->db->addRepository(new Repository('static', $this->db));
        $this->assertCount(1, $this->db->getRepositories());
    }
    
    /**
     * @expectedException FlatFileDbException
     */
    public function testGetUnknownRepository()
    {
        $this->db->getRepository('movies2');
    }
    
}