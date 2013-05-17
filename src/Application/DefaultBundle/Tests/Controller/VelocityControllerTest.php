<?php

namespace Application\DefaultBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VelocityControllerTest extends WebTestCase
{
    public function setupCrawler($url = '/velocity') {
        $this->client = static::createClient();

        $this->crawler = $this->client->request('GET', $url);
    }

    public function testIndex()
    {
        $this->setupCrawler();
        $this->assertTrue(
            $this->crawler->filter('html:contains("Velocity")')->count() > 0
        );
    }
    
    public function testTeam() {
        $this->setupCrawler('/velocity/team/gaia');
        $this->assertTrue(
            $this->crawler->filter('html:contains("Gaia")')->count() > 0
        );
    }
    
}
