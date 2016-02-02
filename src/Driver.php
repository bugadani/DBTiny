<?php

namespace DBTiny;

use InvalidArgumentException;
use DBTiny\Driver\Statement;

abstract class Driver
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var Platform
     */
    private $platform;

    /**
     * @param Platform    $platform
     */
    public function __construct(Platform $platform)
    {
        $this->platform = $platform;
    }

    /**
     * @return Platform
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    public function getQueryBuilder()
    {
        if (!isset($this->queryBuilder)) {
            $this->queryBuilder = new QueryBuilder($this);
        }

        return $this->queryBuilder;
    }

    /**
     * Call a function guarded by a transaction.
     *
     * @param callable $function The function to guard. First argument is the driver, the rest are the arguments
     * passed to the method.
     *
     * @return mixed The return value of the guarded function
     */
    public function inTransaction(callable $function)
    {
        if (!is_callable($function)) {
            throw new InvalidArgumentException('$function must be callable.');
        }
        $this->beginTransaction();
        try {
            if (func_num_args() === 1) {
                $returnValue = $function($this);
            } else {
                $args    = func_get_args();
                $args[0] = $this;
                $returnValue = call_user_func_array($function, $args);
            }
            $this->commit();
            return $returnValue;
        } catch (\PDOException $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function fetch($query, array $params = null)
    {
        return $this->query($query, $params)->fetch();
    }

    public function fetchAll($query, array $params = null)
    {
        return $this->query($query, $params)->fetchAll();
    }

    public function fetchColumn($query, array $params = null, $columnNumber = 0)
    {
        return $this->query($query, $params)->fetchColumn($columnNumber);
    }

    abstract public function setAttribute($name, $value);

    abstract public function getAttribute($name);

    /**
     * @param       $query
     * @param array $params
     *
     * @return Statement
     */
    abstract public function query($query, array $params = null);

    /**
     * @param $query
     *
     * @return Statement
     */
    abstract public function prepare($query);

    abstract public function lastInsertId($name = null);

    abstract public function beginTransaction();

    abstract public function commit();

    abstract public function rollBack();

    /**
     * Quotes an identifier (e.g. table, column) to be safe to use in queries.
     *
     * @param string $identifier
     *
     * @return string The quoted identifier.
     */
    public function quoteIdentifier($identifier)
    {
        return $this->platform->quoteIdentifier($identifier);
    }

    abstract public function quoteLiteral($literal, $type = null);
}
