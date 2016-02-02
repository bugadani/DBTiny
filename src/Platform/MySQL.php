<?php

namespace DBTiny\Platform;

use DBTiny\Platform;

class MySQL extends Platform
{
    const MAX_LIMIT = '18446744073709551615';

    /**
     * @inheritdoc
     */
    public function quoteIdentifier($identifier)
    {
        return "`{$identifier}`";
    }

    public function getTableListingQuery()
    {
        return 'SHOW TABLES';
    }

    public function getTableDetailingQuery($table)
    {
        return 'DESCRIBE ' . $this->quoteIdentifier($table);
    }

    public function getLimitAndOffset($limit, $offset)
    {
        if (isset($offset)) {
            return ' LIMIT ' . ($limit ? : self::MAX_LIMIT) . ' OFFSET ' . $offset;
        }
        if (isset($limit)) {
            return ' LIMIT ' . $limit;
        }

        return '';
    }
}
