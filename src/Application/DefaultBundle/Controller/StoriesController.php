<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Estimates;
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
      switch ($teamname) {
        case 'gaia':
          $team = 'Gaia';
          $backlog = 9248;
          break;
        case 'ateam':
          $team = 'A-Team';
          $backlog = 7869;
          break;
        case 'raptor':
          $team = 'Raptor';
          $backlog = 9862;
          break;
        case 'prime':
          $team = 'Prime';
          $backlog = 9555;
          break;
      }

      return $this->render(
          'ApplicationDefaultBundle:Stories:index.html.twig', 
          array(
            'team'      => $team,
            'teamname'  => $teamname,
            'backlog'   => $backlog
            )
      );      
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
