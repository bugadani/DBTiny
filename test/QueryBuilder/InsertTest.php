<?php

namespace DBTiny\QueryBuilder;

use DBTiny\Driver;
use DBTiny\Platform;

class InsertTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Driver
     */
    private $driver;

    public function setUp()
    {
        $platform     = $this->getMockForAbstractClass(Platform::class);
        $this->driver = $this->getMockForAbstractClass(Driver::class, [$platform]);
    }

    public function testEmptyInsert()
    {
        $insert = new Insert($this->driver);
        $insert->into('table');
        $this->assertEquals('INSERT INTO table () VALUES ()', $insert->get());
    }

    public function testInsertWithValue()
    {
        $insert = new Insert($this->driver);
        $insert
            ->into('table')
            ->values(
                [
                    'a' => '?',
                    'b' => '?'
                ]
            )
            ->set('c', '?');
        $this->assertEquals('INSERT INTO table (a, b, c) VALUES (?, ?, ?)', $insert->get());
    }

    public function testInsertWithPositionalParameters()
    {
        $insert = new Insert($this->driver);
        $insert
            ->into('table')
            ->values(
                [
                    'a' => $insert->createPositionalParameter('foo'),
                    'b' => $insert->createPositionalParameter('bar')
                ]
            );
        $this->assertEquals(
            'INSERT INTO table (a, b) VALUES (?, ?)',
            $insert->get()
        );
    }

    public function testInsertWithNamedParameters()
    {
        $insert = new Insert($this->driver);
        $insert
            ->into('table')
            ->values(
                [
                    'a' => $insert->createNamedParameter('foo'),
                    'b' => $insert->createNamedParameter('bar')
                ]
            );
        $this->assertEquals(
            'INSERT INTO table (a, b) VALUES (:parameter0, :parameter1)',
            $insert->get()
        );
    }
}
