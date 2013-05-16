<?php

namespace Application\DefaultBundle\Tests\Lib\Observer;
use Application\DefaultBundle\Tests\SetupTests;
use Application\DefaultBundle\Lib\Observer;


class ObserverTest extends SetupTests
{
    public $observer;
    public function setup() {
        parent::setup();
        $this->getMocks('json_response_themes');
        $this->ebclient->setBacklog(array('0'));
        $this->observer = new Observer($this->ebclient, $this->memcache, array('1'));
    }
    
    public function testGetData() {
        $conditions = array(
            'backlogtotalstoriespermonth',
            'acceptancerate',
            'stories',
            'backlogestimatespread',
            'totalstoriespermonth',
            'departmentvelocity',
            'story',
            'deptstats',
            'teamstats',
        );
        foreach($conditions AS $condition) {
            $testable = $this->observer->getData($condition, 1);
            $this->assertTrue(is_array($testable));
        }
        
        
    }
}