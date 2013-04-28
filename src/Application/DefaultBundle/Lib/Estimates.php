<?php

namespace Application\DefaultBundle\Lib;

class Estimates {
    /**
     * Return all the aggregated estimate data.
     **/
    public function getEstimateDataByMonth($stories) {

        $aggEstimates = array();
        foreach($stories AS $story) {

            // Grab the date of the story and put it into a [year][month][estimate] = counter
            list($year, $month, $dayTime) = explode("-", $story['created_at']);

            // Does the year exist?
            if (!isset($aggEstimates[$year])) 
                $aggEstimates[$year] = null;

            // Does the month exist?
            if (!isset($aggEstimates[$year][$month])) 
                $aggEstimates[$year][$month] = null;

            // Does the estimate exist?
            $est = round($story['score_50']);
            if (!isset($aggEstimates[$year][$month][$est])) 
                $aggEstimates[$year][$month][$est] = 0;
            
            $aggEstimates[$year][$month][$est]++;
            
        }

        // Now sort it nicely (Does this need to be done)
        // Now, sort the honkin bitches.
        $new = $aggEstimates;
        $aggEstimates = array();
        foreach($new AS $year => $month) {
            foreach($month AS $monthNo => $estimates) {
                ksort($estimates);
                $aggEstimates[$year][$monthNo] = $estimates;
            }
        }

        return $aggEstimates;
    }    
}