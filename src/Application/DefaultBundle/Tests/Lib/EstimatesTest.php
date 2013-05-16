<?php

namespace Application\DefaultBundle\Tests\EstimatesTest;
use Application\DefaultBundle\Tests\SetupTests;


class EstimatesTest extends SetupTests
{
    
    public function testGetEstimateDataByMonthReturnsArray() {
        $this->getMocks('json_response_themes');
        $ebclient = $this->ebclient->setBacklog(array('0'));
        $testable = $this->estimates->getEstimateDataByMonth($ebclient->getStoriesFromTheme());
        $this->assertTrue(is_array($testable));
        
        // Check the array looks like it should
        $this->assertTrue(count($testable) == 2);
        $this->assertArrayHasKey('2012', $testable);
        $this->assertArrayHasKey('09', $testable['2012']);
    }
    
    public function testgettotalStoriesPerMonthReturnsGoogleVisArray() {
        $this->getMocks('json_response_themes');
        $this->ebclient->setBacklog(array('0'));
        $testable = $this->estimates->gettotalStoriesPerMonth();
        $this->assertTrue(is_array($testable)); 
         // Check the array looks like it should
        $this->assertTrue(count($testable) == 2);
        $this->assertArrayHasKey('cols', $testable);
        $this->assertArrayHasKey('rows', $testable);
        $this->assertArrayHasKey('label', $testable['cols'][0]);
    }
    
    public function testgetEstimateSpreadPerMonthReturnsGoogleVisArray() {
        $this->getMocks('json_response_themes');
        $this->ebclient->setBacklog(array('0'));
        $testable = $this->estimates->getEstimateSpreadPerMonth();
        $this->assertTrue(is_array($testable)); 
         // Check the array looks like it should
        $this->assertTrue(count($testable) == 2);
        $this->assertArrayHasKey('cols', $testable);
        $this->assertArrayHasKey('rows', $testable);
        $this->assertArrayHasKey('label', $testable['cols'][0]);
    }    


}