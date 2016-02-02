<?php

namespace DBTiny;

use DBTiny\QueryBuilder\Delete;
use DBTiny\QueryBuilder\Expression;
use DBTiny\QueryBuilder\Insert;
use DBTiny\QueryBuilder\Select;
use DBTiny\QueryBuilder\Update;

class QueryBuilder
{
    /**
     * @var Driver
     */
    private $driver;

    /**
     * @var AbstractQueryBuilder
     */
    private $lastQuery;

    /**
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param $column
     *
     * @return Select
     */
    public function select($column)
    {
        $builder         = new Select($this->driver);
        $this->lastQuery = $builder;

        $columns = is_array($column) ? $column : func_get_args();

        return $builder->select($columns);
    }

    /**
     * @param       $table
     * @param array $values
     *
     * @return Insert
     */
    public function insert($table, array $values = null)
    {
        $builder         = new Insert($this->driver);
        $this->lastQuery = $builder;

        if ($values) {
            $builder->values($values);
        }

        return $builder->into($table);
    }

    /**
     * @param       $table
     * @param array $values
     *
     * @return Update
     */
    public function update($table, array $values = null)
    {
        $builder         = new Update($this->driver);
        $this->lastQuery = $builder;

        if ($values) {
            $builder->values($values);
        }

        return $builder->update($table);
    }

    /**
     * @param $from
     *
     * @return Delete
     */
    public function delete($from)
    {
        $builder               = new Delete($this->driver);
        $this->lastQuery       = $builder;

        return $builder->from($from);
    }

    /**
     * @return Expression
     */
    public function expression()
    {
        return new Expression();
    }

    /**
     * @param $value
     *
     * @return string
     * @throws \LogicException
     */
    public function createPositionalParameter($value)
    {
        if (!$this->lastQuery) {
            throw new \LogicException('Cannot set parameter when no query is being built.');
        }

        return $this->lastQuery->createPositionalParameter($value);
    }

    /**
     * @param $value
     *
     * @return string
     * @throws \LogicException
     */
    public function createNamedParameter($value)
    {
        if (!$this->lastQuery) {
            throw new \LogicException('Cannot set parameter when no query is being built.');
        }

        return $this->lastQuery->createNamedParameter($value);
    }
}
