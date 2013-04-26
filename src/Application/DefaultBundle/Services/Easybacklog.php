<?php

namespace Application\DefaultBundle\Services;

class Easybacklog {

	public function __construct($guzzle) {
		var_dump($guzzle);
	}

	public function getStuff() {
		return 'stuff';
	}
	
}