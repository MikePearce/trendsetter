<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Estimates;
use Symfony\Component\HttpFoundation\JsonResponse;

class EstimatesController extends Controller
{    
    /**
     * Index, just show a summary/overview
     * @return $response object
     **/
    public function indexAction()
    {

        return $this->render(
            'ApplicationDefaultBundle:Estimates:index.html.twig', 
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
          'ApplicationDefaultBundle:Estimates:index.html.twig', 
          array(
            'teamname'  => $teams[$teamname]['name'],
            'backlog'   => $teams[$teamname]['backlog']
            )
      );      

    }
}