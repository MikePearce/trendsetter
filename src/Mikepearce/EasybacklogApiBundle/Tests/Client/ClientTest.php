<?php

namespace Mikepearce\EasybacklogApiBundle\Tests\Controller;
use Mikepearce\EasybacklogApiBundle\Client\Client;

class ClientClientTest extends \Guzzle\Tests\GuzzleTestCase
{
    /**
     * What we're testing
     **/
    public $ebclient;
    
    /**
     * Create the mock clases
     **/
    public function setup() {    

        // Guzzle
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $plugin->addResponse(new \Guzzle\Http\Message\Response(200));
        $mockedClient = new \Guzzle\Service\Client();
        $mockedClient->addSubscriber($plugin);
        $this->setMockBasePath(__DIR__ . DIRECTORY_SEPARATOR . '../TestData');
        $this->setMockResponse($mockedClient, 'json_response');
        
        // Mock memcache
        $json_string = trim($this->getMockResponse('json_response')->getBody());
        $memcache = $this->getMock('memcache', array('get', 'set'));
        $memcache->expects($this->any())
                 ->method('get')
                 ->will($this->returnValue($json_string));
        $memcache->expects($this->any())
                 ->method('set')
                 ->will($this->returnValue(true));

        $this->ebclient = new Client(
            $memcache, 
            $mockedClient, 
            'xxxxxxxxxx', 
            '123'
        );                 
    }

    /**
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\setBacklog
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\setAccountId
     **/
    public function testSetBacklogReturnsObject()
    {   
        
        $ebclient = $this->ebclient->setBacklog(array('0'))
                                   ->setBacklog('123');
        $this->assertInstanceOf('Mikepearce\EasybacklogApiBundle\Client\Client', $ebclient);
    }

    /**
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\getJsonFromApi
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\addDataToCache
     **/
    public function testGetJsonFromApiReturnsJson() {

        $this->assertNotNull(
            json_decode($this->ebclient->getJsonFromApi('http://www.google.com'))
        );
    }

    /**
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\getJsonFromApi
     **/
    public function testGetDataApiData() {
        $this->assertNotNull(
            json_decode($this->ebclient->getJsonFromApi('http://www.google.com'))
        );  
    }
    
    /**
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\getThemes(true);
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\getThemes(false);
     */
    public function testGetThemes() {
        $this->ebclient->setBacklog(rand(1, 202323));
        $this->assertTrue(
            is_array($this->ebclient->getThemes())
        );
        $this->assertTrue(
            is_array($this->ebclient->getThemes(true))
        );
    }
    
    /**
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\getThemes(true);
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\getThemes(false);
     */
    public function testGetSprints() {
        $this->ebclient->setBacklog(rand(1, 202323));
        $this->assertTrue(
            is_array($this->ebclient->getSprints())
        );
        $this->assertTrue(
            is_array($this->ebclient->getSprints(true))
        );
    }    
    
    /**
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\getVelocityStats();
     */
    public function testGetVelocityStats() {
        $this->ebclient->setBacklog(rand(1, 202323));
        $this->assertTrue(
            is_array($this->ebclient->getVelocityStats())
        );
        $this->assertArrayHasKey(
            'velocity_stats', $this->ebclient->getVelocityStats()
        );
        $this->assertArrayHasKey(
            'velocity_complete', $this->ebclient->getVelocityStats()
        );
    }   
}
