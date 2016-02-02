<?php

namespace DBTiny\QueryBuilder;

use DBTiny\AbstractQueryBuilder;

abstract class AbstractUpdate extends AbstractQueryBuilder
{
    protected $values = [];

    public function values(array $values)
    {
        $this->values = array_merge($this->values, $values);

        return $this;
    }

    public function set($name, $value)
    {
        $this->values[$name] = $value;

        return $this;
    }
}
