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
     * Do some setup
     **/
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
    }

    /**
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\setBacklog
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\setAccountId
     **/
    public function testSetBacklogReturnsObject() {   
        $this->getMocks();
        $ebclient = $this->ebclient->setBacklog(array('0'))
                                   ->setBacklog('123');
        $this->assertInstanceOf('Mikepearce\EasybacklogApiBundle\Client\Client', $ebclient);
    }

    /**
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\getJsonFromApi
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\addDataToCache
     **/
    public function testGetJsonFromApiReturnsJson() {
        $this->getMocks();
        $this->assertNotNull(
            json_decode($this->ebclient->getJsonFromApi('http://www.google.com'))
        );
    }

    /**
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\getJsonFromApi
     **/
    public function testGetDataApiData() {
        $this->getMocks();
        $this->assertNotNull(
            json_decode($this->ebclient->getJsonFromApi('http://www.google.com'))
        );  
    }
    
    /**
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\getThemes(true);
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\getThemes(false);
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\loopBacklogs;
     */
    public function testGetThemes() {
        $this->getMocks('json_response_themes');
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
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\loopBacklogs;
     */
    public function testGetSprints() {
        $this->getMocks('json_response_sprints');
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
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\loopBacklogs;
     */
    public function testGetVelocityStats() {
        $this->getMocks('json_response_stats');
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
    
    /**
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\getStoriesFromTheme;
     */
    public function testGetStoriesFromTheme() {
        $this->getMocks('json_response_themes');
        $this->ebclient->setBacklog(rand(1, 202323));
        $this->assertTrue(
            is_array($this->ebclient->getStoriesFromTheme())
        );
        
    }
    
    /**
     * @covers Mikepearce\EasybacklogApiBindle\Client\Client\getStory;
     */
    public function testGetStory() {
        $this->getMocks('json_response_themes');
        $this->ebclient->setBacklog(rand(1, 202323));
        $this->assertTrue(
            is_array($this->ebclient->getStory(rand(1, 202323)))
        );
        
    }    
}
