<?php

namespace DBTiny\Test\Driver;

use DBTiny\Driver\MySQLConnectionConfig;

class MySQLConnectionConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $config = new MySQLConnectionConfig();

        $config->host   = 'localhost';
        $config->dbname = 'test';

        $this->assertEquals('mysql:host=localhost;dbname=test', (string)$config);
    }

    public function testFromArray()
    {
        $config = new MySQLConnectionConfig(
            [
                'host'   => 'localhost',
                'dbname' => 'test'
            ]
        );


        $this->assertEquals('mysql:host=localhost;dbname=test', (string)$config);
    }
}