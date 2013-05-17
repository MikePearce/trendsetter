<?php

namespace Application\DefaultBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function setupCrawler($url = '/') {
        $this->client = static::createClient();

        $this->crawler = $this->client->request('GET', $url);
    }

    public function testIndex()
    {
        $this->setupCrawler();
        $this->assertTrue($this->crawler->filter('html:contains("Development Trends")')->count() > 0);
    }
    
    public function testData() {
        $this->setupCrawler('/data/deptstats');
        $this->assertRegExp(
            '/current_velocity/',
            $this->client->getResponse()->getContent()
        );        
    }
    
}
