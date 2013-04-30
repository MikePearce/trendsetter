<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Estimates;

class EstimatesController extends Controller
{    
    public function indexAction()
    {
        // array(9248,7869,9555)
        

        // Grab the easybacklog service
        $easybacklogClient = $this->get('mikepearce_easybacklog_api');
        $easybacklogClient->setAccountId('477')
                          ->setBacklog(array(9248,7869,9555));

        // Grab the trendsetting bundle
        $estimates = new Estimates();
        $estimate_data = $estimates->getEstimateDataByMonth(
            $easybacklogClient->getStoriesFromTheme()
        );

        return $this->render(
            'ApplicationDefaultBundle:Estimates:index.html.twig', 
            array('stuff' => 'stuff', 'chart_data' => $estimate_data)
        );
    }

}
