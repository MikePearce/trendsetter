<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{    
    public function indexAction()
    {
        // array(9248,7869,9555)

        // Grab the easybacklog service
        $easybacklogClient = $this->get('mikepearce_easybacklog_api');
        $easybacklogClient->setAccountId('477')
                          ->setBacklog(9248);

        return $this->render(
            'ApplicationDefaultBundle:Default:index.html.twig', 
            array('stuff' => $easybacklogClient->getThemes(true))
        );
    }

}
