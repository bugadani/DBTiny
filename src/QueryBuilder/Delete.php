<?php

namespace DBTiny\QueryBuilder;

use DBTiny\AbstractQueryBuilder;
use DBTiny\QueryBuilder\Traits\LimitTrait;
use DBTiny\QueryBuilder\Traits\OrderByTrait;
use DBTiny\QueryBuilder\Traits\WhereTrait;
use UnexpectedValueException;

class Delete extends AbstractQueryBuilder
{
    use WhereTrait;
    use LimitTrait;
    use OrderByTrait;

    private $table;

    public function from($table)
    {
        $this->table = $table;

        return $this;
    }

    public function get()
    {
        return $this->getFromPart() .
        $this->getWhere() .
        $this->getOrderByPart() .
        $this->getLimitingPart();
    }

    private function getFromPart()
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('Delete query must have a FROM clause.');
        }

        return 'DELETE FROM ' . $this->table;
    }
}
