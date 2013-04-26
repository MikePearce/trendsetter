<?php

namespace MikePearce\EasybacklogApiBundle\Client;

class Client {

    // HTTP Service
    private $guzzle;

    /**
     * Where are we going to store it?
     **/
    private $json_file = 'stories.json';

    /**
     * 
     **/
    public function __construct($guzzle, $api_key, $accountid, $backlogs) {
        var_dump($backlogs);
        // Set it
        $this->guzzle = $guzzle;
    }

    public function getEndpoint() {
        return $this->guzzle->getBaseUrl();
    }
    
}