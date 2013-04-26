<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{    
    public function indexAction()
    {
        // Grab the easybacklog service
        $easybacklogClient = $this->get('Easybacklog');

        return $this->render(
            'ApplicationDefaultBundle:Default:index.html.twig', 
            array('stuff' => $easybacklogClient->getEndpoint())
        );
    }

}
