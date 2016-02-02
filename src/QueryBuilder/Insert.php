<?php

namespace DBTiny\QueryBuilder;

use UnexpectedValueException;

class Insert extends AbstractUpdate
{
    private $table;

    public function into($table)
    {
        $this->table = $table;

        return $this;
    }

    public function get()
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('Insert query must have an INTO clause.');
        }
        $keys   = implode(', ', array_keys($this->values));
        $values = implode(', ', $this->values);

        return "INSERT INTO {$this->table} ({$keys}) VALUES ({$values})";
    }

    public function query(array $parameters = [])
    {
        parent::query($parameters);

        return $this->driver->lastInsertId();
    }
}
