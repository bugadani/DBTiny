<?php

namespace DBTiny\QueryBuilder\Traits;

use DBTiny\Platform;

trait LimitTrait
{
    private $limit;
    private $offset;

    public function setMaxResults($limit)
    {
        $this->limit = (int)$limit;

        return $this;
    }

    public function setFirstResult($offset)
    {
        $this->offset = (int)$offset;

        return $this;
    }

    /**
     * @return Platform
     */
    abstract public function getPlatform();

    public function getLimitingPart()
    {
        return $this->getPlatform()->getLimitAndOffset($this->limit, $this->offset);
    }
}
