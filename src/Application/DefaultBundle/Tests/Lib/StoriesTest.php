<?php

namespace Application\DefaultBundle\Tests\Lib\StoriesTest;
use Application\DefaultBundle\Tests\SetupTests;

class StoriesTest extends SetupTests
{
    public $stories;
    public $ebclient;
    
    public function testGetMonthlyAcceptance() {
        $this->getMocks('json_response_sprints');
        $ebclient = $this->ebclient->setBacklog(array('0'));
        $testable = $this->stories->getMonthlyAcceptance($ebclient->getSprints());
        $this->assertTrue(is_int($testable));
    }
}