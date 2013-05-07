<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Estimates;
use Application\DefaultBundle\Lib\Stories;
use Symfony\Component\HttpFoundation\JsonResponse;

class StoriesController extends Controller
{    
    public function indexAction()
    {

        return $this->render(
            'ApplicationDefaultBundle:Stories:index.html.twig', 
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

    /**
     * Velocity
     **/
    public function velocityAction() {

      $teams = $this->container->getParameter('teams');
      
      return $this->render(
          'ApplicationDefaultBundle:Stories:index.html.twig', 
          array(
            'teamname'  => $teams[$teamname]['name'],
            'backlog'   => $teams[$teamname]['backlog']
            )
      );
    }

    /**
     * Return the acceptance rate for all teams across all sprints
     **/
    public function acceptancerateAction() {
      $easybacklogClient = $this->get('mikepearce_easybacklog_api');
      $teams = $this->container->getParameter('teams');

      $backlogs = array();
      foreach($teams AS $team) {
        $backlogs[] = $team['backlog'];
      }

      $easybacklogClient->setAccountId('477')
                        ->setBacklog($backlogs);

      $stories = new Stories($easybacklogClient);

      var_dump($stories->getAcceptanceRateByMonth());

      return $this->response();
    }

    public function dataAction($type, $backlog) {
      
      $easybacklogClient = $this->get('mikepearce_easybacklog_api');
      $easybacklogClient->setAccountId('477')
                        ->setBacklog($backlog);
      
      $estimates = new Estimates($easybacklogClient);
      
      switch($type) {
        case 'totalstoriespermonth':
        case 'backlogtotalstoriespermonth':
          $data = $estimates->gettotalStoriesPerMonth();
          break;
        default:
          throw new \Exception("I don't know what ". $type ."is.", 1);
      }
      
      $response = new JsonResponse();
      $response->setData($data);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }
}
