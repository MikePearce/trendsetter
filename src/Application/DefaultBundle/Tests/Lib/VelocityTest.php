<?php

namespace Application\DefaultBundle\Tests\Lib\VelocityTest;
use Application\DefaultBundle\Tests\SetupTests;

class VelocityTest extends SetupTests
{
    public $velocity;
    public $ebclient;
    
    public function testgetVelocityForGoogleVis() {
        $this->getMocks('json_response_sprints');
        $this->ebclient->setBacklog(array('0'));
        $testable = $this->velocity->getVelocityForGoogleVis();
        $this->assertTrue(is_array($testable)); 
         // Check the array looks like it should
        $this->assertTrue(count($testable) == 2);
        $this->assertArrayHasKey('cols', $testable);
        $this->assertArrayHasKey('rows', $testable);
        $this->assertArrayHasKey('label', $testable['cols'][0]);
    }
    
    public function testgetCurrentTeamVelocity() {
        $this->getMocks('json_response_sprints');
        $this->ebclient->setBacklog(array('0'));
        $testable = $this->velocity->getCurrentTeamVelocity(1);
        $this->assertTrue(is_array($testable)); 
    }
    
    public function testGetCurrentVelocity() {
        $this->getMocks('json_response_sprints', 1);
        $this->ebclient->setBacklog(array('0'));
        $testable = $this->velocity->getCurrentVelocity();
        $this->assertTrue(is_int($testable)); 
    }    
}