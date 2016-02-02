<?php

namespace DBTiny\QueryBuilder\Traits;

use DBTiny\Platform;

trait GroupByTrait
{
    private $groupByFields = [];

    public function setGroupBy($field)
    {
        $this->groupByFields = is_array($field) ? $field : func_get_args();

        return $this;
    }

    public function groupBy($field)
    {
        $fields              = is_array($field) ? $field : func_get_args();
        $this->groupByFields = array_merge($this->groupByFields, $fields);

        return $this;
    }

    protected function getGroupByPart()
    {
        if (empty($this->groupByFields)) {
            return '';
        }

        return ' GROUP BY ' . join(', ', $this->groupByFields);
    }
}
