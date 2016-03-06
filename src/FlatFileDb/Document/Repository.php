<?php
namespace FlatFileDb\Document;

use DirectoryIterator;
use FlatFileDb\Db;
use FlatFileDb\Exception\Exception;
use FlatFileDb\Hydrator\HydratorInterface;
use FlatFileDb\QueryBuilder;
use FlatFileDb\Storage\StorageInterface;
use FlatFileDb\Util\Keygen;

class Repository implements RepositoryInterface
{
    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var DocumentInterface
     */
    protected $documentPrototype;

    /**
     *
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     *
     * @var Db
     */
    protected $db;

    /**
     *
     * @var StorageInterface
     */
    protected $storage;

    /**
     *
     * @param string $name
     * @param Db $db
     * @param RepositoryConfig $config
     */
    public function __construct($name, Db $db, RepositoryConfig $config = null)
    {
        if (null == $config) {
            $config = new RepositoryConfig;
        }
        $this->name = $name;
        $this->db = $db;
        $this->documentPrototype = $config->getDocumentPrototype();
        $this->hydrator = $config->getHydrator();
        $this->storage = $config->getStorage();

        $this->db->getConfiguration()->getDirectory()->createIfNotExists($name);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return DocumentInterface
     */
    public function getDocumentPrototype()
    {
        return $this->documentPrototype;
    }

    /**
     *
     * @return string
     */
    public function getPath()
    {
        return $this->db->getConfiguration()->getDirectory()->getRepositoryPath($this->name);
    }

    /**
     *
     * @param string|DocumentInterface $idOrDocument
     * @return string
     * @throws Exception
     */
    public function getDocumentPath($idOrDocument)
    {
        if (is_object($idOrDocument)) {
            $idOrDocument = $idOrDocument->getIdentifier();
        }
        if (null === $idOrDocument) {
            throw new Exception(__METHOD__ . ': error, document has no identifier.');
        }
        return sprintf('%s/%s', $this->getPath(), $idOrDocument);
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder($this);
    }

    /**
     *
     * @param string $id
     * @return bool
     */
    public function remove($id)
    {
        $document = $this->find($id);
        $this->applyCallback($document, 'onPreRemove');
        $result = $this->storage->delete($this->getDocumentPath($id));
        $this->applyCallback($document, 'onPostRemove');
        return $result;
    }

    /**
     *
     * @param array|DocumentInterface[] $ids
     */
    public function removeAll($ids)
    {
        foreach ($ids as $id) {
            $this->remove($id);
        }
    }

    /**
     *
     * @param array $criteria
     * @return int
     */
    public function removeBy(array $criteria)
    {
        $removed = 0;
        $docs = $this->findBy($criteria);
        foreach ($docs as $doc) {
            if ($this->delete($doc)) {
                $removed++;
            }
        }
        return $removed;
    }

    /**
     *
     * @param string|null $id
     * @return DocumentInterface
     */
    public function find($id)
    {
        return $this->storage->read($this->getDocumentPath($id));
    }

    /**
     *
     * @return DocumentInterface[]|null
     */
    public function findAll()
    {
        return $this->createQueryBuilder()->getQuery()->execute();
    }

    /**
     *
     * @param array $criteria
     * @return DocumentInterface[]
     */
    public function findBy(array $criteria)
    {
        return $this->createQueryBuilder()->setCriteria($criteria)->getQuery()->execute();
    }

    /**
     *
     * @param array $criteria
     * @return DocumentInterface
     */
    public function findOneBy(array $criteria)
    {
        return $this->createQueryBuilder()->setCriteria($criteria)->getQuery()->execute()->current();
    }

    /**
     * Store document.
     *
     * @param DocumentInterface|DocumentInterface[] $document
     * @return boolean
     * @throws Exception
     */
    public function save(DocumentInterface $document)
    {
        $this->applyCallback($document, 'onPreSave');
        if (null === $document->getIdentifier()) {
            $document->setIdentifier(Keygen::getToken());
        }

        $result = $this->storage->write($this->getDocumentPath($document), $document);

        if ($result == 0) {
            unlink($this->getDocumentPath($document));
            throw new Exception('Failed to store document: ' . $document->getIdentifier());
        }
        $this->applyCallback($document, 'onPostSave');
        return true;
    }

    /**
     *
     * @param string|DocumentInterface $idOrDocument
     * @param bool $deep
     * @return boolean
     */
    public function has($idOrDocument, $deep = false)
    {
        if (is_object($idOrDocument)) {
            $idOrDocument = $idOrDocument->getIdentifier();
        }
        $filename = $this->getDocumentPath($idOrDocument);
        if (file_exists($filename)) {
            if ($deep) {
                $doc = $this->storage->read();
                if ($doc && $doc->getIdentifier() == $idOrDocument) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function getIterator()
    {
        $iterator = new DirectoryIterator($this->getPath());
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                yield $this->storage->read($file->getRealPath(), false);
            }
        }
    }

    protected function applyCallback(DocumentInterface $document, $method)
    {
        if ($document instanceof CallbackProviderInterface) {
            call_user_func_array(array($document, $method), array());
        }
    }
}