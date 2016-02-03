<?php

namespace DBTiny\Driver;

class MySQLConnectionConfig
{
    public $host;
    public $port;
    public $dbname;
    public $unix_socket;
    public $charset;

    public function __construct($params = [])
    {
        if (!is_array($params)) {
            throw new \InvalidArgumentException('$params must be an array or a MySQLConfig object');
        }
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function __toString()
    {
        $parts = ['host', 'port', 'dbname', 'unix_socket', 'charset'];

        $dsnParts = [];
        foreach ($parts as $part) {
            if (isset($this->{$part})) {
                $dsnParts[] = "{$part}={$this->{$part}}";
            }
        }

        return 'mysql:' . implode(';', $dsnParts);
    }


}