<?php

namespace Application\DefaultBundle\Tests\Lib\Observer;
use Application\DefaultBundle\Tests\SetupTests;
use Application\DefaultBundle\Lib\Observer;


class ObserverTest extends SetupTests
{
    public $observer;
    
    public function testGetData() {
        $conditions = array(
            'backlogtotalstoriespermonth'   => 'themes',
            'acceptancerate'                => 'sprints',
            'stories'                       => 'themes',  
            'backlogestimatespread'         => 'themes',
            'totalstoriespermonth'          => 'themes',
            'departmentvelocity'            => 'sprints',
            'story'                         => 'themes',
            'deptstats'                     => 'themes',
            // Need some way of passing in two responses b4 this works
            //'teamstats'                     => 'themes',
        );
        foreach($conditions AS $condition => $response) {
            $this->getMocks('json_response_'. $response);
            $this->ebclient->setBacklog(array('0'));
            $this->observer = new Observer(
                $this->ebclient, 
                $this->memcache, 
                array('teamname' => array('name' => 'name', 'backlog' => '9248'))
            );
            $testable = $this->observer->getData($condition, 1);
            $this->assertTrue(is_array($testable));
        }
        
    }
}