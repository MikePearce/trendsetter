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
     * @param $guzzle - DI injected $guzzle
     * @param $api_key - Your easybacklog.com API key
     * @param $accountid - your easybacklog.com account id
     * @param $backlogs - An array of backlogs you're interested in.
     * @return void
     **/
    public function __construct($guzzle, $api_key, $accountid, $backlogs) {
        // Set it
        $this->guzzle = $guzzle;
        $this->api_key = $api_key;
        $this->accountid = $accountid;
        $this->backlogs = $backlogs;
    }

    /**
     * @return string - your uRL endpoint
     **/
    public function getEndpoint() {
        return $this->guzzle->getBaseUrl();
    }

    /**
     * Whatever it is, construct the endpoint and return the json
     **/
    private function getJsonFromApi($some_path = null) {
        return $this->guzzle->get($some_path)->send()->getBody();
    }

    public function getStuff() {
        return $this->getJsonFromApi();
    }
    
}