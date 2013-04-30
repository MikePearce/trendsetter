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
          // foreach ($estimates AS $size => $count) {
            
          // }
          $row['c'] = array_merge($rowx, $rowy);
          $rows[] = $row;
          $row = $rowx = $rowy = array();
        }
        
      }

      $return_array = array('cols' => $columns, 'rows' => $rows);

      //$array =  json_decode('{"cols":[{"id":"","label":"Month","pattern":"","type":"string"},{"id":"","label":"Size: 1","pattern":"","type":"number"},{"id":"","label":"Size: 2","pattern":"","type":"number"},{"id":"","label":"Size: 3","pattern":"","type":"number"},{"id":"","label":"Size: 5","pattern":"","type":"number"},{"id":"","label":"Size: 8","pattern":"","type":"number"},{"id":"","label":"Size: 13","pattern":"","type":"number"},{"id":"","label":"Size: 20","pattern":"","type":"number"}],"rows":[{"c":[{"v":"2012/10","f":null},{"v":2,"f":null},{"v":0,"f":null},{"v":4,"f":null},{"v":6,"f":null},{"v":3,"f":null},{"v":0,"f":null},{"v":0,"f":null}]},{"c":[{"v":"2012/12","f":null},{"v":1,"f":null},{"v":0,"f":null},{"v":2,"f":null},{"v":1,"f":null},{"v":1,"f":null},{"v":0,"f":null},{"v":0,"f":null}]},{"c":[{"v":"2012/09","f":null},{"v":0,"f":null},{"v":1,"f":null},{"v":0,"f":null},{"v":1,"f":null},{"v":2,"f":null},{"v":1,"f":null},{"v":1,"f":null}]},{"c":[{"v":"2012/11","f":null},{"v":4,"f":null},{"v":5,"f":null},{"v":11,"f":null},{"v":8,"f":null},{"v":3,"f":null},{"v":0,"f":null},{"v":0,"f":null}]},{"c":[{"v":"2012/07","f":null},{"v":0,"f":null},{"v":0,"f":null},{"v":0,"f":null},{"v":1,"f":null},{"v":0,"f":null},{"v":3,"f":null},{"v":1,"f":null}]},{"c":[{"v":"2012/08","f":null},{"v":0,"f":null},{"v":0,"f":null},{"v":0,"f":null},{"v":1,"f":null},{"v":0,"f":null},{"v":0,"f":null},{"v":0,"f":null}]},{"c":[{"v":"2013/01","f":null},{"v":2,"f":null},{"v":1,"f":null},{"v":3,"f":null},{"v":7,"f":null},{"v":3,"f":null},{"v":2,"f":null},{"v":0,"f":null}]},{"c":[{"v":"2013/02","f":null},{"v":1,"f":null},{"v":6,"f":null},{"v":5,"f":null},{"v":13,"f":null},{"v":10,"f":null},{"v":2,"f":null},{"v":0,"f":null}]},{"c":[{"v":"2013/03","f":null},{"v":1,"f":null},{"v":7,"f":null},{"v":4,"f":null},{"v":14,"f":null},{"v":4,"f":null},{"v":1,"f":null},{"v":0,"f":null}]},{"c":[{"v":"2013/04","f":null},{"v":7,"f":null},{"v":5,"f":null},{"v":4,"f":null},{"v":10,"f":null},{"v":0,"f":null},{"v":0,"f":null},{"v":0,"f":null}]}],"p":null}', true);
      $response = new JsonResponse();
      $response->setData($return_array);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

}
