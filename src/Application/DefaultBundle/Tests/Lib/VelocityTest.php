<?php

namespace Application\DefaultBundle\Tests\Lib\StoriesTest;
use Application\DefaultBundle\Tests\SetupTests;

class StoriesTest extends SetupTests
{
    public $stories;
    public $ebclient;
    
    public function testGetMonthlyAcceptance() {
        $this->getMocks('json_response_sprints');
        $this->ebclient->setBacklog(array('0'));
        $testable = $this->stories->getAcceptanceRateForGoogleVis();
        $this->assertTrue(is_array($testable)); 
         // Check the array looks like it should
        $this->assertTrue(count($testable) == 2);
        $this->assertArrayHasKey('cols', $testable);
        $this->assertArrayHasKey('rows', $testable);
        $this->assertArrayHasKey('label', $testable['cols'][0]);
    }
    
    public function testGetCurrentAcceptanceRate() {
        $this->getMocks('json_response_sprints', 1);
        $testable = $this->stories->getCurrentAcceptanceRate();
        $this->assertTrue(is_int($testable)); 
    }    
    
    public function testGetStoriesByBacklog() {
        $this->getMocks('json_response_themes');
        $this->ebclient->setBacklog(array('0'));
        $testable = $this->stories->getStoriesByBacklog();
        $this->assertTrue(is_array($testable));
    }
    
    public function testGetSingleStory() {
        $this->getMocks('json_response_themes');
        $this->ebclient->setBacklog(array('0'));
        $testable = $this->stories->getStoriesByBacklog();
        $this->assertTrue(is_array($testable));
    }    
}