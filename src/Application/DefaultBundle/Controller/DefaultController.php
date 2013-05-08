<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Stories;
use Application\DefaultBundle\Lib\Velocity;

class DefaultController extends Controller
{    
    public function indexAction()
    {
        $teams = $this->container->getParameter('teams');

        $backlogs = array();
        foreach($teams AS $team) {
            $backlogs[] = $team['backlog'];
        }
        $easybacklogClient = $this->get('mikepearce_easybacklog_api');
        $easybacklogClient->setAccountId('477')
                          ->setBacklog($backlogs);

        $vel = new Velocity($easybacklogClient, $this->get('memcached'));
        $str = new Stories($easybacklogClient, $this->get('memcached'));
        return $this->render(
            'ApplicationDefaultBundle:Default:index.html.twig', 
            array(
                'current_velocity'      => $vel->getCurrentVelocity(),
                'current_acceptance'    => $str->getCurrentAcceptanceRate(),
            )
        );
    }

}
