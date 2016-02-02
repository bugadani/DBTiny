<?php

namespace DBTiny\QueryBuilder;

use DBTiny\AbstractQueryBuilder;
use DBTiny\QueryBuilder\Traits\GroupByTrait;
use DBTiny\QueryBuilder\Traits\HavingTrait;
use DBTiny\QueryBuilder\Traits\JoinTrait;
use DBTiny\QueryBuilder\Traits\LimitTrait;
use DBTiny\QueryBuilder\Traits\OrderByTrait;
use DBTiny\QueryBuilder\Traits\WhereTrait;
use UnexpectedValueException;

class Select extends AbstractQueryBuilder
{
    use WhereTrait;
    use HavingTrait;
    use JoinTrait;
    use LimitTrait;
    use OrderByTrait;
    use GroupByTrait;

    private $columns = [];
    private $lock    = false;
    private $from;

    public function select($column)
    {
        $this->columns = is_array($column) ? $column : func_get_args();

        return $this;
    }

    public function addSelect($column)
    {
        $columns       = is_array($column) ? $column : func_get_args();
        $this->columns = array_merge($this->columns, $columns);

        return $this;
    }

    public function from($from, $alias = null)
    {
        if ($from instanceof Select) {
            $from = "({$from->get()})";
            if ($alias === null) {
                throw new \InvalidArgumentException('Subqueries must have an alias');
            }
        } else if ($alias === null) {
            $alias = $from;
        }

        $this->from[ $alias ] = $from;

        return $this;
    }

    public function lockForUpdate($lock = true)
    {
        $this->lock = $lock;

        return $this;
    }

    public function get()
    {
        return $this->getSelectPart() .
               $this->getFromPart() .
               $this->getWhere() .
               $this->getGroupByPart() .
               $this->getHaving() .
               $this->getOrderByPart() .
               $this->getLimitingPart() .
               $this->getLockPart();
    }

    private function getFromPart()
    {
        if (empty($this->from)) {
            throw new UnexpectedValueException('Select query must have a FROM clause.');
        }

        $from      = ' FROM ';
        $separator = '';

        foreach ($this->from as $alias => $table) {
            $from .= $separator . $table;
            if ($alias !== $table) {
                $from .= ' ' . $alias;
            }

            $from .= $this->getJoinPart($alias);
            $separator = ', ';
        }

        return $from;
    }


    private function getLockPart()
    {
        if (!$this->lock) {
            return '';
        }

        return ' FOR UPDATE';
    }

    private function getSelectPart()
    {
        return 'SELECT ' . implode(', ', $this->columns);
    }
}
