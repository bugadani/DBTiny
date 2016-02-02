<?php

namespace DBTiny\QueryBuilder\Traits;

use DBTiny\Platform;

trait OrderByTrait
{
    private $orderByFields = [];

    public function setOrderBy($field, $order = 'ASC')
    {
        $this->orderByFields = [];

        return $this->orderBy($field, $order);
    }

    public function orderBy($field, $order = 'ASC')
    {
        $order = strtoupper($order);
        if ($order !== 'ASC') {
            $order = 'DESC';
        }
        $this->orderByFields[$field] = $field . ' ' . $order;

        return $this;
    }

    public function getOrderByPart()
    {
        if (empty($this->orderByFields)) {
            return '';
        }

        return ' ORDER BY ' . join(', ', $this->orderByFields);
    }
}
