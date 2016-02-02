<?php

namespace DBTiny\Test;

use DBTiny\Driver;
use DBTiny\Platform;
use DBTiny\QueryBuilder;
use DBTiny\QueryBuilder\Delete;
use DBTiny\QueryBuilder\Expression;
use DBTiny\QueryBuilder\Insert;
use DBTiny\QueryBuilder\Select;
use DBTiny\QueryBuilder\Update;

class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QueryBuilder
     */
    private $builder;

    public function setUp()
    {
        $platform = $this->getMockForAbstractClass(Platform::class);
        $driver   = $this->getMockForAbstractClass(Driver::class, [$platform]);

        $driver->expects($this->any())
            ->method('getPlatform')
            ->will($this->returnValue($platform));

        $this->builder = new QueryBuilder($driver);
    }

    public function testThatTheCorrectTypesAreReturned()
    {
        $select = $this->builder->select('*');
        $this->assertInstanceOf(
            Select::class,
            $select
        );

        $insert = $this->builder->insert('table');
        $this->assertInstanceOf(
            Insert::class,
            $insert
        );

        $update = $this->builder->update('table');
        $this->assertInstanceOf(
            Update::class,
            $update
        );

        $delete = $this->builder->delete('table');
        $this->assertInstanceOf(
            Delete::class,
            $delete
        );

        $expr = $this->builder->expression();
        $this->assertInstanceOf(
            Expression::class,
            $expr
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testParameterTypesCannotBeMixed()
    {
        $this->builder->select('*');
        $this->assertEquals('?', $this->builder->createPositionalParameter(0));
        $this->assertEquals(':parameter3', $this->builder->createNamedParameter(0));
    }

    public function testParameterMethods()
    {
        $this->builder->select('*');
        $this->assertEquals('?', $this->builder->createPositionalParameter(0));
        $this->assertEquals(['?', '?'], $this->builder->createPositionalParameter([0, 1]));
        $this->builder->select('*');
        $this->assertEquals(':parameter0', $this->builder->createNamedParameter(0));
        $this->assertEquals([':parameter1', ':parameter2'], $this->builder->createNamedParameter([0, 1]));
    }
}
