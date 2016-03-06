<?php
namespace FlatFileDbTest\Document;

use DateTime;
use FlatFileDb\Configuration;
use FlatFileDb\Db;
use FlatFileDb\Document\Repository;
use FlatFileDb\Document\RepositoryConfig;
use PHPUnit_Framework_TestCase;

class RepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Db
     */
    protected $db;

    /**
     *
     * @var Repository
     */
    protected $repository;

    protected function setUp()
    {
        $this->db = new Db(new Configuration(array(
            'directory' => 'database'
        )));

        $config = new RepositoryConfig;
        $config->setDocumentPrototype(new SampleDoc);
        $this->repository = new Repository('static', $this->db, $config);
        $this->db->addRepository($this->repository);
    }

    public function testGetName()
    {
        $this->assertEquals('static', $this->repository->getName());
    }

    public function testSave()
    {
        $config = new RepositoryConfig;
        $config->setDocumentPrototype(new SampleDoc);
        $repository = new Repository('dynamic', $this->db, $config);

        $doc = new SampleDoc;
        $doc->name = 'DOC 1';
        $doc->date = new DateTime;
        $result = $repository->save($doc);
        $this->assertTrue($result);
        $this->assertFileExists($repository->getDocumentPath($doc));
        unlink($repository->getDocumentPath($doc));
    }

    public function testFind()
    {
        $doc = new SampleDoc;
        $doc->name = 'DOC';
        $this->repository->save($doc);

        $doc = $this->repository->find($doc->getIdentifier());
        $this->assertInstanceOf(SampleDoc::class, $doc);
        $this->assertEquals('DOC', $doc->name);
        $this->repository->remove($doc);
    }

    public function testFindAll()
    {
        $doc1 = new SampleDoc;
        $doc1->name = 'DOC 1';
        $this->repository->save($doc1);

        $doc2 = new SampleDoc;
        $doc2->name = 'DOC 2';
        $this->repository->save($doc2);

        $docs = $this->repository->findAll();
        $this->assertCount(2, $docs);
        $this->repository->removeAll([$doc1, $doc2]);
    }

    public function testRemove()
    {
        $doc = new SampleDoc();
        $this->repository->save($doc);

        $this->assertFileExists($this->repository->getDocumentPath($doc));
        $this->repository->remove($doc);
        $this->assertFileNotExists($this->repository->getDocumentPath($doc));
    }
}