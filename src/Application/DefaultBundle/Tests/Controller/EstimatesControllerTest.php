<?php

namespace Application\DefaultBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EstimatesControllerTest extends WebTestCase
{
    public function setupCrawler($url = '/estimates') {
        $this->client = static::createClient();

        $this->crawler = $this->client->request('GET', $url);
    }

    public function testIndex()
    {
        $this->setupCrawler();
        $this->assertTrue(
            $this->crawler->filter('html:contains("Spread of estimate sizes per month")')->count() > 0
        );
    }
    
    public function testTeam() {
        $this->setupCrawler('/estimates/team/gaia');
        $this->assertTrue(
            $this->crawler->filter('html:contains("Spread of estimate sizes per month: Gaia")')->count() > 0
        );
    }
    
}
