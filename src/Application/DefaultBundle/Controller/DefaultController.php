<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\TrendsetterBundle\Lib\Estimates;

class DefaultController extends Controller
{    
    public function indexAction()
    {
        // array(9248,7869,9555)

        // Grab the easybacklog service
        $easybacklogClient = $this->get('mikepearce_easybacklog_api');
        $easybacklogClient->setAccountId('477')
                          ->setBacklog(9248);

        // Grab the trendsetting bundle
        $estimates = new Estimates();
        $estimate_data = $estimates->getEstimateDataByMonth(
            $easybacklogClient->getStoriesFromTheme()
        );

        $chart_data = '';

        foreach($estimate_data AS $year => $month) {
            foreach ($month AS $monthNo => $estimates) {
              $chart_data .= "['". $year ."/".$monthNo."',"; 
              foreach (array(0, 1, 3, 5, 8, 13, 20) AS $est){
                  $chart_data .= (isset($estimates[$est]) ? $estimates[$est] : 0) .",";
              }
              $chart_data .= "],\n";
            }
        }

        return $this->render(
            'ApplicationDefaultBundle:Default:index.html.twig', 
            array('stuff' => 'stuff', 'chart_data' => $chart_data)
        );
    }

}
