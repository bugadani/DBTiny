<?php

namespace DBTiny\QueryBuilder;

use DBTiny\Driver;
use DBTiny\Platform;

class DeleteTest extends \PHPUnit_Framework_TestCase
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

    public function testDelete()
    {
        $delete = new Delete($this->driver);
        $delete->from('table');
        $delete->where('c=d');

        $this->assertEquals('DELETE FROM table WHERE c=d', $delete->get());
    }
}
