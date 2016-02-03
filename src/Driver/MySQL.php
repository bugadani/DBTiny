<?php

namespace DBTiny\Driver;

use DBTiny\Driver;
use DBTiny\Platform;
use DBTiny\Platform\MySQL as MySQLPlatform;

class MySQL extends PDODriver
{
    public function __construct($params, $user, $password, array $options = [])
    {
        parent::__construct(new MySQLPlatform());

        $this->connect($this->constructDsn($params), $user, $password, $options);
    }

    private function constructDsn($params)
    {
        if (is_string($params)) {
            //dsn
            return $params;
        }
        if (!($params instanceof MySQLConnectionConfig)) {
            $params = new MySQLConnectionConfig($params);
        }

        return (string)$params;
    }
}
