<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Estimates;
use Symfony\Component\HttpFoundation\JsonResponse;

class EstimatesController extends Controller
{    
    public function indexAction()
    {

        return $this->render(
            'ApplicationDefaultBundle:Estimates:index.html.twig', 
            array('stuff' => 'stuff')
        );
    }

    public function dataAction() {
      
      // Do the columns
      $columns = array();
      foreach(array(
        'Month'     => 'string', 
        'Size: 1'   => 'number', 
        'Size: 2'   => 'number', 
        'Size: 3'   => 'number', 
        'Size: 5'   => 'number', 
        'Size: 8'   => 'number', 
        'Size: 13'  => 'number', 
        'Size: 20'  => 'number') AS $field => $type) {
        $columns[] = array('id' => '', 'label' => $field, 'pattern' => '', 'type' => $type);
      }

      // Do the data
      $rows = array();
      
      $easybacklogClient = $this->get('mikepearce_easybacklog_api');
      $easybacklogClient->setAccountId('477')
                        ->setBacklog(array(9248,7869,9555));
      $estimates = new Estimates();
      $estimate_data = $estimates->getEstimateDataByMonth(
          $easybacklogClient->getStoriesFromTheme()
      );


      $rows = array();
      foreach($estimate_data AS $year => $month) {
        foreach($month AS $month_no => $estimates) {
          $rowx = array(array('v' => $year ."/". $month_no, 'f' => null));
          foreach (array(1, 2, 3, 5, 8, 13, 20) AS $est) {
            $rowy[] = array('v' => (isset($estimates[$est]) ? $estimates[$est] : 0), 'f' => null);
          }
          $row['c'] = array_merge($rowx, $rowy);
          $rows[] = $row;
          $row = $rowx = $rowy = array();
        }
        
      }

      $response = new JsonResponse();
      $response->setData(array('cols' => $columns, 'rows' => $rows));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

}
