<?php

namespace Application\DefaultBundle\Lib;

class Velocity {

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

        $columns = array();
        foreach(array(
            'Year/Month'     => 'string', 
            'Velocity'   => 'number', 
            ) AS $field => $type) {
                $columns[] = array('id' => '', 'label' => $field, 'pattern' => '', 'type' => $type);
        }

        $sprint_data = $this->easybacklogClient->getSprints();
        $total_points_per_month = $rows = array();
        
        foreach($sprint_data AS $sprint) {

            if (false == $sprint['completed?']) continue;
            // First, workout the month
            list($year, $month, $day_and_time) = explode("-", $sprint['completed_at']);
            if(!isset($monthly_velocity[$year.'/'.$month])) $monthly_velocity[$year.'/'.$month] = array();

            $total_points_per_month[$year.'/'.$month][] = $sprint['total_completed_points'];
        }

        // Now look through and make it fun
        ksort($total_points_per_month);
        foreach($total_points_per_month AS $month => $points_array) {
            $row_label = array(array('v' => $month, 'f' => null));
            $row_data = array(array('v' => (array_sum($points_array) / count($points_array)) , 'f' => null));
            $row['c'] = array_merge($row_label, $row_data);
            $rows[] = $row;
            $row = $row_data = $row_label = array();
        }

        return array('cols' => $columns, 'rows' => $rows);
    }  
}