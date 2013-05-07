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
     **/
    public function __construct(\MikePearce\EasybacklogApiBundle\Client\Client $easybacklogClient) {
        $this->easybacklogClient = $easybacklogClient;
    }

    /**
     * Compute the monthly acceptance rate.
     * @param $stories array - All the stories
     * @return array
     **/
    public function getAcceptanceRateByMonth() {

        $sprint_data = $this->easybacklogClient->getSprints();
        $monthly_velocity = array();
        
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

        // Now we have that, work out the acceptance rate
        // ($actual / $expected * 100)
        $rows = array();
        ksort($monthly_velocity);
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
  
}