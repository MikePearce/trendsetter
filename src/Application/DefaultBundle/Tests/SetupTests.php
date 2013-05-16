<?php

namespace Application\DefaultBundle\Tests;
use Application\DefaultBundle\Lib\Stories;
use Application\DefaultBundle\Lib\Estimates;
use Application\DefaultBundle\Lib\Velocity;
use Mikepearce\EasybacklogApiBundle\Client\Client;

class SetupTests extends \Guzzle\Tests\GuzzleTestCase
{
    public $stories;
    public $estimates;
    public $ebclient;
    
    public function setup() {    
        // Guzzle
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $plugin->addResponse(new \Guzzle\Http\Message\Response(200));
        $this->mockedClient = new \Guzzle\Service\Client();
        $this->mockedClient->addSubscriber($plugin);
        $this->setMockBasePath(__DIR__ . DIRECTORY_SEPARATOR . 'TestData');
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
        
        $this->stories = new Stories($this->ebclient);
        $this->estimates = new Estimates($this->ebclient);
    }   
}