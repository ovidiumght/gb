<?php
namespace FlatFileDb;

use FlatFileDb\Document\RepositoryInterface;
use FlatFileDb\Query;

class QueryBuilder
{
    /**
     * @var array
     */
    protected $criteria;

    /**
     *
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     *
     * @param RepositoryInterface $repository
     * @param array $criteria
     */
    public function __construct(RepositoryInterface $repository, array $criteria = array())
    {
        $this->criteria = $criteria;
        $this->repository = $repository;
    }

    /**
     * Add arbitrary condition.
     *
     * @param string $field
     * @param mixed $value
     * @param string $condition
     * @return QueryBuilder
     */
    public function add($field, $value, $condition)
    {
        $this->criteria[$field] = array(
            $condition => $value
        );
        return $this;
    }

    /**
     *
     * Add "greater than" condition.
     *
     * @param string $field
     * @param int $value
     * @return QueryBuilder
     */
    public function gt($field, $value)
    {
        return $this->add($field, $value, Query::GT);
    }

    /**
     * Add "greater than or equals" condition.
     *
     * @param int $value
     * @return QueryBuilder
     */
    public function gte($field, $value)
    {
        return $this->add($field, $value, Query::GTE);
    }

    /**
     * Add "less than" condition.
     *
     * @param int $value
     * @return QueryBuilder
     */
    public function lt($field, $value)
    {
        return $this->add($field, $value, Query::LT);
    }

    /**
     * Add "less than or equals" condition.
     *
     * @param int $value
     * @return QueryBuilder
     */
    public function lte($field, $value)
    {
        return $this->add($field, $value, Query::LTE);
    }

    /**
     * Add "equals" condition.
     *
     * @param mixed $value
     * @return QueryBuilder
     */
    public function eq($field, $value)
    {
        return $this->add($field, $value, Query::EQ);
    }

    /**
     * Add "not equals" condition.
     *
     * @param mixed $value
     * @return QueryBuilder
     */
    public function neq($field, $value)
    {
        return $this->add($field, $value, Query::NEQ);
    }

    /**
     * Add "in array" condition.
     *
     * @param string $field
     * @param array $values
     * @return QueryBuilder
     */
    public function in($field, array $values)
    {
        return $this->add($field, $values, Query::IN);
    }

    /**
     * Add "not in array" condition.
     *
     * @param string $field
     * @param array $values
     * @return QueryBuilder
     */
    public function notIn($field, array $values)
    {
        return $this->add($field, $values, Query::NOT_IN);
    }

    /**
     * Add "between" condition.
     *
     * @param string $field
     * @param int $min
     * @param int $max
     * @return QueryBuilder
     */
    public function between($field, $min, $max)
    {
        return $this->add($field, array($min, $max), Query::BETWEEN);
    }

    /**
     * Add "not between" condition.
     *
     * @param string $field
     * @param int $min
     * @param int $max
     * @return QueryBuilder
     */
    public function notBetween($field, $min, $max)
    {
        return $this->add($field, array($min, $max), Query::NOT_BETWEEN);
    }

    /**
     * Add "like" regex condition.
     *
     * @param string $field
     * @param string $regex
     * @return QueryBuilder
     */
    public function like($field, $regex)
    {
        return $this->add($field, '/' . trim($regex, '/') . '/', Query::LIKE);
    }

    /**
     * Add "not like" regex condition.
     *
     * @param string $field
     * @param string $regex
     * @return QueryBuilder
     */
    public function notLike($field, $regex)
    {
        return $this->add($field, '/' . trim($field, '/') . '/', Query::NOT_LIKE);
    }

    /**
     * Returns generated criteria.
     *
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     *
     * @param array $criteria
     * @return QueryBuilder
     */
    public function setCriteria(array $criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     *
     * @return Query
     */
    public function getQuery()
    {
        return new Query($this->repository, $this->criteria);
    }

}