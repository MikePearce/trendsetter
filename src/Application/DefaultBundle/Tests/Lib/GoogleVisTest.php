<?php

namespace Application\DefaultBundle\Tests\Lib\GoogleVisTest;
use Application\DefaultBundle\Lib\Googlevis;

class StoriesTest extends \PHPUnit_Framework_TestCase
{
    public $googlevis;
    
    public function setup() {
        $this->googlevis = new Googlevis();
    }
    
    public function testCreateColumns() {
        $testable = $this->googlevis->createColumns(
            array('Year/Month' => 'string', 'Acceptance'   => 'number')
        );
        $this->assertTrue(is_array($testable));
        $this->assertArrayHasKey('id', $testable[0]);
        $this->assertArrayHasKey('label', $testable[0]);
        $this->assertArrayHasKey('pattern', $testable[0]);
        $this->assertArrayHasKey('type', $testable[0]);
    }
    
    public function testCreateDataRow() {
        $testable = $this->googlevis->createDataRow(
            'No of ninjas',
            200
        );
        $this->assertTrue(is_array($testable));
        $this->assertArrayHasKey('c', $testable);
        $this->assertTrue(is_array($testable['c']));
        $this->assertTrue($testable['c'][0]['v'] == 'No of ninjas');
    }
}