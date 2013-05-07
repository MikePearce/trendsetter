<?php

namespace Application\DefaultBundle\Lib;

class Stories {

    /**
     * The easybacklog client
     **/
    private $easybacklogClient;

    /**
     * Set the stuff
     * @param $easybacklogClient obj 
     **/
    public function __construct(\MikePearce\EasybacklogApiBundle\Client\Client $easybacklogClient) {
        $this->easybacklogClient = $easybacklogClient;
    }

    /**
     * Return the velocity per month
     
     **/
    public function getVelocity() {

    }

    /**
     * Compute the monthly acceptance rate.
     * @param $stories array - All the stories
     * @return array
     **/
    public function getAcceptanceRateByMonth() {

        $sprint_data = $this->easybacklogClient->getSprints();
        $monthly_velocity = array();
        
        foreach($sprint_data AS $sprint) {

            if (false == $sprint['completed?']) continue;
            // First, workout the month
            list($year, $month, $day_and_time) = explode("-", $sprint['completed_at']);
            if(!isset($monthly_velocity[$year.'/'.$month])) $monthly_velocity[$year.'/'.$month] = array();

            $monthly_velocity[$year.'/'.$month][] = array(
                'completed' => $sprint['total_completed_points'],
                'committed' => $sprint['total_expected_points'],
            );
        }

        return $monthly_velocity;

    }
  
}