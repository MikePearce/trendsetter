<?php

namespace Application\DefaultBundle\Lib;
use Application\DefaultBundle\Lib\Googlevis;

class Stories {

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

    private function getMonthlyAcceptance($sprint_data) {
        // Iterate through the sprints
        foreach($sprint_data AS $sprint) {

            if (false == $sprint['completed?']) continue;
            // First, workout the month
            list($year, $month, $day_and_time) = explode("-", $sprint['completed_at']);

            if(!isset($monthly_velocity[$year.'/'.$month])) $monthly_velocity[$year.'/'.$month] = array();

            // Then create an array with the expected (committed) vs actual
            $monthly_velocity[$year.'/'.$month][] = array(
                'completed' => $sprint['total_completed_points'],
                'committed' => $sprint['total_expected_points'],
            );
        }
        ksort($monthly_velocity);
        return $monthly_velocity;
    }

    /**
     * Compute the monthly acceptance rate.
     * @param $stories array - All the stories
     * @return array
     **/
    public function getAcceptanceRateForGoogleVis() {

        $monthly_velocity = $this->getMonthlyAcceptance($this->easybacklogClient->getSprints());
        
        // Now we have that, work out the acceptance rate
        // ($actual / $expected * 100)
        $rows = array();

        $googleVis = new Googlevis();
        $columns = $googleVis->createColumns(array('Year/Month' => 'string', 'Acceptance'   => 'number'));
        foreach ($monthly_velocity as $date => $stats_array) {

            $total_completed_points = $total_expected_points = 0;

            foreach($stats_array AS $stats) {
                $total_expected_points += $stats['committed'];
                $total_completed_points += $stats['completed'];
            }
            $rows[] = $googleVis->createDataRow(
                $date,
                ceil(($total_completed_points / $total_expected_points) * 100)
            );
        }

        return array('cols' => $columns, 'rows' => $rows);

    }

    /**
     * Get LAST months acceptance
     * @param $backlogid int - if this is passed, then we're looking for just the team
     * @return int
     **/
    public function getCurrentAcceptanceRate($backlogid = false) {

        $last_month = date("Y/m", strtotime("-1 month"));

        $key =  (false != $backlogid) ? $backlogid : $last_month;
            
        $acceptance = $this->memcached->get(md5($key.'acceptance'));    

        // If not, get it, then put it in memcached
        if (false == $acceptance) {
            $stats_array = $this->getMonthlyAcceptance($this->easybacklogClient->getSprints());
            $total_expected_points = $total_completed_points = 0;

            foreach($stats_array[$last_month] AS $stats) {
                $total_expected_points += $stats['committed'];
                $total_completed_points += $stats['completed'];
            }
            $acceptance = ceil(($total_completed_points / $total_expected_points) * 100);    
            $this->memcached->set(md5($key.'acceptance'), $acceptance);
        }

        return $acceptance;
        
    }
    
    /**
     * Get all the stories
     * @return array
     **/
    public function getStoriesByBacklog() {
        return $this->easybacklogClient->getStoriesFromTheme(true);
    }

    public function getSingleStory($story_id) {
        return $this->easybacklogClient->getStory($story_id);   
    }
  
}