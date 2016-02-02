<?php

namespace DBTiny\QueryBuilder;

use DBTiny\QueryBuilder\Traits\LimitTrait;
use DBTiny\QueryBuilder\Traits\OrderByTrait;
use DBTiny\QueryBuilder\Traits\WhereTrait;
use UnexpectedValueException;

class Update extends AbstractUpdate
{
    use WhereTrait;
    use LimitTrait;
    use OrderByTrait;

    private $table;

    public function update($table)
    {
        $this->table = $table;

        return $this;
    }

    public function get()
    {
        return $this->getUpdatePart() .
        $this->getSetPart() .
        $this->getWhere() .
        $this->getOrderByPart() .
        $this->getLimitingPart();
    }

    private function getUpdatePart()
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('Update query must have a table to update.');
        }

        return 'UPDATE ' . $this->table;
    }

    private function getSetPart()
    {
        $set = ' SET ';

        $first = true;
        foreach ($this->values as $key => $value) {
            if ($first) {
                $first = false;
            } else {
                $set .= ', ';
            }
            $set .= $key . '=' . $value;
        }

        return $set;
    }
}
