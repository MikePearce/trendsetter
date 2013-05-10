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
          'ApplicationDefaultBundle:Velocity:index.html.twig', 
          array(
            'teamname'  => $teams[$teamname]['name'],
            'backlog'   => $teams[$teamname]['backlog']
            )
      );      
    }    
}
