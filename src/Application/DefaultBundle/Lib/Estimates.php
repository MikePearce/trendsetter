<?php

namespace Application\DefaultBundle\Lib;
use Application\DefaultBundle\Lib\Googlevis;

class Estimates {

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
     * Return all the aggregated estimate data.
     * @param $stories array - All the stories
     * @return array
     **/
    public function getEstimateDataByMonth($stories) {

        $aggEstimates = array();
        foreach($stories AS $story) {

            // Grab the date of the story and put it into a 
            // [year][month][estimate] = counter
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

        $new = $aggEstimates;
        $aggEstimates = array();
        foreach($new AS $year => $month) {
            ksort($month);
            foreach($month AS $monthNo => $estimates) {
                ksort($estimates);
                $aggEstimates[$year][$monthNo] = $estimates;
            }
        }
        ksort($aggEstimates);
        return $aggEstimates;
    }    

        /**
     * Get the total of each sized estimate per month
     * @param $estimates Instance of Estimates
     * @param $easybacklogClient Instance of \MikePearce\EasybacklogApiBundle\Client\Client
     * @return array
     **/
    public function gettotalStoriesPerMonth() {
      $googleVis = new Googlevis();
      $columns = $googleVis->createColumns(
          array('Year/Month' => 'string', 'No. of Stories' => 'number')
      );

        $estimate_data = $this->getEstimateDataByMonth(
          $this->easybacklogClient->getStoriesFromTheme()
        );

        $row = $rows = $row_data = $row_label = array();

        foreach($estimate_data AS $year => $month) {
            foreach($month AS $month_no => $stories) {
                foreach($stories AS $story) {
                    $counter = (!isset($counter) ? 0 : $counter);
                    $counter += $story;
                }
                $rows[] = $googleVis->createDataRow(
                  $year ."/". $month_no,
                  $counter
                );
                $counter = 0;
            }
        }
        return array('cols' => $columns, 'rows' => $rows);
    }

    /**
     * Get the total of each sized estimate per month
     * @param $easybacklogClient Instance of \MikePearce\EasybacklogApiBundle\Client\Client
     * @return array
     **/
    public function getEstimateSpreadPerMonth() {

      // Do the columns
      $googleVis = new Googlevis();
      $columns = $googleVis->createColumns(
        array(
          'Month'     => 'string', 
          'Size: 1'   => 'number', 
          'Size: 2'   => 'number', 
          'Size: 3'   => 'number', 
          'Size: 5'   => 'number', 
          'Size: 8'   => 'number', 
          'Size: 13'  => 'number', 
          'Size: 20'  => 'number'
        )
      );
            
      $estimate_data = $this->getEstimateDataByMonth(
          $this->easybacklogClient->getStoriesFromTheme()
      );


      $rows = array();
      foreach($estimate_data AS $year => $month) {
        foreach($month AS $month_no => $estimates) {
          foreach (array(1, 2, 3, 5, 8, 13, 20) AS $est) {
            $row_data[] = (isset($estimates[$est]) ? $estimates[$est] : 0);
          }

          $rows[] = $googleVis->createDataRow(
            $year ."/". $month_no,
            $row_data
          );
          $row = $row_label = $row_data = array();
        }
        
      }

      return array('cols' => $columns, 'rows' => $rows);
    }    
}