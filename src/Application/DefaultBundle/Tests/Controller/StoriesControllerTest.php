<?php

namespace Application\DefaultBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StoriesControllerTest extends WebTestCase
{
    public function setupCrawler($url = '/stories') {
        $this->client = static::createClient();

        $this->crawler = $this->client->request('GET', $url);
    }

    public function testIndex()
    {
        $this->setupCrawler();
        $this->assertTrue(
            $this->crawler->filter('html:contains("Total stories created per month")')->count() > 0
        );
    }
    
    public function testTeam() {
        $this->setupCrawler('/stories/team/gaia');
        $this->assertTrue(
            $this->crawler->filter('html:contains("Total stories created per month: Gaia")')->count() > 0
        );
    }
    
    public function testAcceptanceRate() {
        $this->setupCrawler('/stories/acceptancerate');
        $this->assertTrue(
            $this->crawler->filter('html:contains("Acceptance Rate")')->count() > 0
        );        
    }
    
    public function testAcceptanceRateTeam() {
        $this->setupCrawler('/stories/acceptancerateteam/gaia');
        $this->assertTrue(
            $this->crawler->filter('html:contains("Gaia")')->count() > 0
        );        
    }    
    
}
