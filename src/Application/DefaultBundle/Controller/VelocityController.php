<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Velocity;
use Symfony\Component\HttpFoundation\JsonResponse;

class VelocityController extends Controller
{    
    public function indexAction()
    {

        return $this->render(
            'ApplicationDefaultBundle:Velocity:index.html.twig', 
            array('stuff' => 'stuff')
        );
    }

    /**
     * Return data PER team
     * $param $team string - the team name
     * @return $response object
     **/
    public function teamAction($teamname) {
      $teams = $this->container->getParameter('teams');
      return $this->render(
          'ApplicationDefaultBundle:Stories:index.html.twig', 
          array(
            'teamname'  => $teams[$teamname]['name'],
            'backlog'   => $teams[$teamname]['backlog']
            )
      );      
    }    

    public function dataAction($type, $backlog) {

      $easybacklogClient = $this->get('mikepearce_easybacklog_api');
      $easybacklogClient->setAccountId('477')
                        ->setBacklog($backlog);
      
      $velocity = new Velocity($easybacklogClient);
      
      switch($type) {
        case 'departmentvelocity':
          $data = $velocity->getVelocity();
          break;
        default:
          throw new \Exception("I don't know what ". $type ."is.", 1);
      }

      //$data =  json_decode('{"cols":[{"id":"","label":"Year/Month","pattern":"","type":"string"},{"id":"","label":"Velocity","pattern":"","type":"number"}],"rows":[{"c":[{"v":"2012/09","f":null},{"v":23,"f":null}]},{"c":[{"v":"2012/10","f":null},{"v":20,"f":null}]},{"c":[{"v":"2012/11","f":null},{"v":26,"f":null}]},{"c":[{"v":"2012/12","f":null},{"v":24,"f":null}]}],"p":null} ');
      
      
      $response = new JsonResponse();
      $response->setData($data);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }
}
