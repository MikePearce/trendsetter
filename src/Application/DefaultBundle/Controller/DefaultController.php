<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Estimates;

class DefaultController extends Controller
{    
    public function indexAction()
    {
        return $this->render(
            'ApplicationDefaultBundle:Default:index.html.twig', 
            array('stuff' => 'stuff')
        );
    }

}
