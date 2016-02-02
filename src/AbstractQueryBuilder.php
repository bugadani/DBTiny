<?php

namespace DBTiny;

abstract class AbstractQueryBuilder
{
    /**
     * @var Driver
     */
    protected $driver;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var null|boolean
     */
    private $namedParameters;

    /**
     * Number of query parameters
     */
    private $parameterCounter = 0;

    /**
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return Platform
     */
    public function getPlatform()
    {
        return $this->driver->getPlatform();
    }

    public function __toString()
    {
        return $this->get();
    }

    public function setParameter($num, $value)
    {
        $this->parameters[ $num ] = $value;

        return $num;
    }

    public function query(array $parameters = [])
    {
        return $this->driver->query($this->get(), $parameters + $this->parameters);
    }

    public function createPositionalParameter($value)
    {
        if ($this->namedParameters === true) {
            throw new \LogicException('Cannot mix named and positional parameters in a query.');
        } else {
            $this->namedParameters = false;
        }

        if (is_array($value)) {
            return array_map([$this, 'createPositionalParameter'], $value);
        }

        $this->setParameter($this->parameterCounter++, $value);

        return '?';
    }

    public function createNamedParameter($value)
    {
        if ($this->namedParameters === false) {
            throw new \LogicException('Cannot mix named and positional parameters in a query.');
        } else {
            $this->namedParameters = true;
        }

        if (is_array($value)) {
            return array_map([$this, 'createNamedParameter'], $value);
        }

        $name = ':parameter' . $this->parameterCounter++;

        return $this->setParameter($name, $value);
    }

    abstract public function get();
}
