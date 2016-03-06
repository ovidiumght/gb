<?php

namespace FlatFileDb;

use FlatFileDb\Document\DocumentInterface;
use FlatFileDb\Document\Repository;
use FlatFileDb\Document\RepositoryInterface;
use FlatFileDb\Exception\Exception;

class Query
{
    /**
     * Greater than.
     */
    const GT = '$gt';
    /**
     * Greater than or equals.
     */
    const GTE = '$gte';

    /**
     * Not greater than.
     */
    const NGT = '$ngt';

    /**
     * Not greater than or equals.
     */
    const NGTE = '$ngte';

    /**
     * Less than.
     */
    const LT = '$lt';

    /**
     * Less than or equals.
     */
    const LTE = '$lte';

    /**
     * Not less than
     */
    const NLT = '$nlt';

    /**
     * Not less than or equals
     */
    const NLTE = '$nlte';

    /**
     * Equals.
     */
    const EQ = '$eq';

    /**
     * Not equals.
     */
    const NEQ = '$neq';

    /**
     * In array
     */
    const IN = '$in';

    /**
     * Not in array
     */
    const NOT_IN = '$notIn';

    /**
     * Between integer values
     */
    const BETWEEN = '$between';

    /**
     * Not between integer values
     */
    const NOT_BETWEEN = '$notBetween';

    /**
     * Like pattern
     */
    const LIKE = '$like';

    /**
     * Not like pattern
     */
    const NOT_LIKE = '$notLike';

    /**
     *
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     *
     * @var array
     */
    protected $criteria = array();

    /**
     *
     * @param Repository $repository
     * @param array $criteria
     */
    public function __construct(RepositoryInterface $repository, array $criteria = null)
    {
        $this->repository = $repository;

        if (null !== $criteria) {
            $this->criteria = $criteria;
        }
    }

    /**
     *
     * @param RepositoryInterface $repository
     * @param array $query
     * @throws Exception
     */
    public function execute(array $query = null)
    {
        /* @var $docs DocumentInterface[] */
        $docs = $this->repository->getIterator();
        $prototype = $this->repository->getDocumentPrototype();
        if (!is_array($query)) {
            $query = $this->criteria;
        }

        foreach ($docs as $doc) {
            // if no criteria = any doc matches.
            if (null == $query) {
                yield $this->repository->getHydrator()->hydrate(new $prototype, $doc);
                continue;
            }

            foreach ($query as $field => $criteria) {
                if (is_array($criteria)) {
                    foreach ($criteria as $condition => $pattern) {
                        if (!array_key_exists($field, $doc)) {
                            throw new Exception('No such field: ' . $field . ' in ' . get_class($prototype));
                        }
                        if ($this->match($condition, $pattern, $doc[$field])) {
                            yield $this->repository->getHydrator()->hydrate(new $prototype, $doc);
                        }
                    }
                } else {
                    if (!array_key_exists($field, $doc)) {
                        throw new Exception('No such field: ' . $field . ' in ' . get_class($prototype));
                    }
                    if ($this->match(Query::EQ, $criteria, $doc[$field])) {
                        yield $this->repository->getHydrator()->hydrate(new $prototype, $doc);
                    }
                }
            }
        }
    }

    /**
     *
     * @param string $condition
     * @param mixed $pattern
     * @param mixed $fieldValue
     * @return bool
     */
    protected function match($condition, $pattern, $fieldValue)
    {
        switch ($condition) {
            case Query::GT;
                return $fieldValue > $pattern;
            case Query::GTE;
                return $fieldValue >= $pattern;
            case Query::LT;
                return $fieldValue < $pattern;
            case Query::LTE;
                return $fieldValue <= $pattern;
            case Query::EQ;
                return $fieldValue == $pattern;
            case Query::NEQ;
                return $fieldValue != $pattern;
            case Query::BETWEEN;
                list ($min, $max) = $pattern;
                return $fieldValue >= $min && $fieldValue <= $max;
            case Query::NOT_BETWEEN;
                list ($min, $max) = $pattern;
                return $fieldValue > $min && $fieldValue < $max;
            case Query::IN;
                return in_array($fieldValue, $pattern);
            case Query::NOT_IN;
                return !in_array($fieldValue, $pattern);
            case Query::LIKE;
                return preg_match($pattern, $fieldValue);
            case Query::NOT_LIKE;
                return !preg_match($pattern, $fieldValue);
        }
    }
}
