<?php

namespace Application\DefaultBundle\Services;

class Easybacklog {

	public function __construct() {
		//var_dump($guzzle);
	}

	public function getStuff() {
		return 'stuff';
	}

	/**
     * Injecting guzzle is optional
     **/
    public function setGuzzle($guzzle = null) {
        var_dump($guzzle);
    }
	
}