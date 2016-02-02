<?php

namespace DBTiny\QueryBuilder;

use DBTiny\Driver;
use DBTiny\Platform;

class UpdateTest extends \PHPUnit_Framework_TestCase
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

    public function testUpdate()
    {
        $update = new Update($this->driver);
        $update->update('table')
               ->set('a', '?')
               ->set('b', '?')
               ->values(
                   [
                       'c' => '?',
                       'd' => '?'
                   ]
               )
               ->where('c=d');

        $this->assertEquals('UPDATE table SET a=?, b=?, c=?, d=? WHERE c=d', $update->get());
    }
}
