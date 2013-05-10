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
            'backlog'   => $teams[$teamname]['backlog'],
            'datatype'  => 'totalstoriespermonth',
            'storydata' => true
            )
      );      
    }  

    /**
     * Return Acceptancerate PER team
     * $param $team string - the team name
     * @return $response object
     **/
    public function acceptancerateteamAction($teamname) {
      $teams = $this->container->getParameter('teams');
      return $this->render(
          'ApplicationDefaultBundle:Stories:acceptance.html.twig', 
          array(
            'teamname'  => $teams[$teamname]['name'],
            'backlog'   => $teams[$teamname]['backlog'],
            'datatype' => 'acceptancerate',
            'charttype' => 'line'
            )
      );
    }

    /**
     * Return the acceptance rate for all teams across all sprints
     **/
    public function acceptancerateAction() {
        return $this->render(
            'ApplicationDefaultBundle:Stories:acceptance.html.twig', 
            array(
              'datatype' => 'acceptancerate',
              'charttype' => 'line'
            )
        );
    }

}
