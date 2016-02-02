<?php

namespace DBTiny\Driver;

use DBTiny\Driver;
use DBTiny\Platform;
use DBTiny\Platform\MySQL as MySQLPlatform;

class MySQL extends PDODriver
{
    public function __construct(array $params, $user, $password, array $options = [])
    {
        parent::__construct(new MySQLPlatform());

        $this->connect($this->constructDsn($params), $user, $password, $options);
    }

    private function constructDsn(array $params)
    {
        if (isset($params['dsn'])) {
            return $params['dsn'];
        }
        $dsn = 'mysql:';

        $parts = ['host', 'port', 'dbname', 'unix_socket', 'charset'];
        foreach ($parts as $part) {
            if (isset($params[ $part ])) {
                $dsn .= "{$part}={$params[$part]};";
            }
        }

        return $dsn;
    }
}
