<?php

namespace Mikepearce\EasybacklogApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
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

        // Mock memcache
        $memcache = $this->getMock('memcache', array('get', 'set'));
        $memcache->expects($this->any())
                 ->method('get')
                 ->will($this->returnValue('{"json":"rocks"}'));
        $memcache->expects($this->any())
                 ->method('set')
                 ->will($this->returnValue(true));        

        // Guzzle
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $plugin->addResponse(new \Guzzle\Http\Message\Response(200));
        $mockedClient = new \Guzzle\Service\Client();
        $mockedClient->addSubscriber($plugin);
        $this->setMockBasePath(__DIR__ . DIRECTORY_SEPARATOR . '../TestData');
        $this->setMockResponse($mockedClient, 'json_response');

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
        
        $ebclient = $this->ebclient->setBacklog(array('0'))->setBacklog('123');
        $this->assertInstanceOf('Mikepearce\EasybacklogApiBundle\Client\Client', $ebclient);
    }

    /**
     * @covers Mikepearce\EasybacklogApiBundle\Client\Client\getJsonFromApi
     **/
    public function testGetJsonFromApiReturnsJson() {

        $this->assertNotNull(
            json_decode($this->ebclient->getJsonFromApi('http://www.google.com'))
        );
        
    }
}
