<?php

namespace Application\DefaultBundle\Tests\TracTest;
use Application\DefaultBundle\Tests\SetupTests;
use Application\DefaultBundle\Lib\Trac;


class TracTest extends SetupTests
{    
    public function __construct() {
        $this->memcache = $this->getMock('memcache', array('get', 'set'));
        $this->memcache->expects($this->any())
                 ->method('get')
                 ->will($this->returnValue('xxx'));
        $this->memcache->expects($this->any())
                 ->method('set')
                 ->will($this->returnValue(true));

    }
    
    public function testGetTicketByPriority() {
        $trac = new Trac($this->memcache);
        $testable = $trac->getTicketByPriority();
        $this->assertTrue(count($testable) == 2);
        $this->assertArrayHasKey('cols', $testable);
        $this->assertArrayHasKey('rows', $testable);
        $this->assertArrayHasKey('label', $testable['cols'][0]);
    }    


}