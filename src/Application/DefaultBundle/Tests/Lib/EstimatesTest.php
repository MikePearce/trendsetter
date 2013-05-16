<?php

namespace Application\DefaultBundle\Tests\Controller;
use Application\DefaultBundle\Lib\Estimates;
use Mikepearce\EasybacklogApiBundle\Client\Client;

class ClientClientTest extends \Guzzle\Tests\GuzzleTestCase
{
    public $estimates;
    public $ebclient;
    
    public function setup() {    
        // Guzzle
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $plugin->addResponse(new \Guzzle\Http\Message\Response(200));
        $this->mockedClient = new \Guzzle\Service\Client();
        $this->mockedClient->addSubscriber($plugin);
        $this->setMockBasePath(__DIR__ . DIRECTORY_SEPARATOR . '../TestData');
    }
    
    /**
     * Create the mock object
     * @param string $response_file
     */
    public function getMocks($response_file = 'json_response') {
        $this->setMockResponse($this->mockedClient, $response_file);
        
        // Mock memcache
        $memcache = $this->getMock('memcache', array('get', 'set'));
        $memcache->expects($this->any())
                 ->method('get')
                 ->will($this->returnValue($this->getMockResponse($response_file)->getBody()));
        $memcache->expects($this->any())
                 ->method('set')
                 ->will($this->returnValue(true));

        $this->ebclient = new Client(
            $memcache, 
            $this->mockedClient, 
            'xxxxxxxxxx', 
            '123'
        );                
        
        $this->estimates = new Estimates($this->ebclient);
    }    
    
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