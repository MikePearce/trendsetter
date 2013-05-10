<?php

namespace Application\DefaultBundle\Lib;
use Application\DefaultBundle\Lib\Googlevis;

class Velocity {

    /**
     * The easybacklog client
     **/
    private $easybacklogClient;

    /**
     * Set the stuff
     * @param $easybacklogClient obj 
     * @param $memcached obj
     **/
    public function __construct(
        \MikePearce\EasybacklogApiBundle\Client\Client $easybacklogClient,
        $memcached = false
    ) {
        $this->easybacklogClient = $easybacklogClient;
        $this->memcached = $memcached;
    }

    private function getPointsPerMonth($sprint_data) {

        foreach($sprint_data AS $sprint) {

            if (false == $sprint['completed?']) continue;
            // First, workout the month
            list($year, $month, $day_and_time) = explode("-", $sprint['completed_at']);
            if(!isset($monthly_velocity[$year.'/'.$month])) $monthly_velocity[$year.'/'.$month] = array();

            $total_points_per_month[$year.'/'.$month][] = $sprint['total_completed_points'];
        }

        // Now look through and make it fun
        ksort($total_points_per_month);

        return $total_points_per_month;
    }

    public function getVelocityForGoogleVis() {
        $googleVis = new Googlevis();
        $columns = $googleVis->createColumns(
            array('Year/Month' => 'string', 'Velocity'   => 'number')
        );

        $total_points_per_month = $rows = array();
        $total_points_per_month = $this->getVelocity();

        $rows = array();
        foreach($total_points_per_month AS $month => $points_array) {
            $rows[] = $googleVis->createDataRow(
                $month,
                ceil((array_sum($points_array) / count($points_array)))
            );
        }

        return array('cols' => $columns, 'rows' => $rows);   
    }

    /**
     * Return the velocity per month
     
     **/
    public function getVelocity() {
        return $this->getPointsPerMonth($this->easybacklogClient->getSprints());
    }  


    /**
     * Get LAST months velocity
     * @return int
     **/
    public function getCurrentVelocity() {

        // Get the month and see if it's in memcached
        $last_month = date("Y/m", strtotime("-1 month"));
        $velocity = $this->memcached->get(md5($last_month.'velocity'));

        // If not, get it, then put it in memcached
        if (false == $velocity) {
            $tppm = $this->getPointsPerMonth($this->easybacklogClient->getSprints());
            $velocity = ceil(array_sum($tppm[$last_month]) / count($tppm[$last_month]));    
            $this->memcached->set(md5($last_month.'velocity'), $velocity);
        }

        return $velocity;
        
    }
}